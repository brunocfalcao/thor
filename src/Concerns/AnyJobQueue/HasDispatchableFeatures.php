<?php

namespace Nidavellir\Thor\Concerns\AnyJobQueue;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

trait HasDispatchableFeatures
{
    public static function dispatch()
    {
        DB::transaction(function () {
            // Handle jobs without an index first
            $jobsWithoutIndex = self::where('status', 'pending')
                ->whereNull('index')
                ->where(function ($query) {
                    $query->whereNull('dispatch_after')
                        ->orWhere('dispatch_after', '<=', now());
                })
                ->lockForUpdate()
                ->get();

            foreach ($jobsWithoutIndex as $job) {
                $job->updateToDispatched();

                // Instantiate the job with properly resolved arguments
                $jobInstance = self::instantiateJobWithArguments($job->class, $job->arguments);

                // Attach the job instance to the coreJobQueue
                $jobInstance->coreJobQueue = $job;

                // Dispatch the job
                Queue::pushOn($job->queue, $jobInstance);
            }

            // Handle indexed jobs by block_uuid
            $blocks = self::where('status', 'pending')
                ->whereNotNull('index')
                ->groupBy('block_uuid')
                ->pluck('block_uuid');

            foreach ($blocks as $blockUuid) {
                // Fetch the maximum completed index for this block
                $maxCompletedIndex = self::where('block_uuid', $blockUuid)
                    ->where('status', 'completed')
                    ->max('index');

                // Determine the next index to process
                $nextIndex = $maxCompletedIndex + 1;

                // Check if all jobs in indices less than `nextIndex` are completed
                $incompleteJobs = self::where('block_uuid', $blockUuid)
                    ->where('index', '<', $nextIndex)
                    ->where('status', '!=', 'completed')
                    ->exists();

                if ($incompleteJobs) {
                    continue; // Wait until all jobs in previous indices are completed
                }

                // Fetch all jobs in the next index
                $jobsToDispatch = self::where('block_uuid', $blockUuid)
                    ->where('index', $nextIndex)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->get();

                if ($jobsToDispatch->isEmpty()) {
                    continue; // No jobs to dispatch for this index
                }

                // Dispatch all jobs for the next index in parallel
                foreach ($jobsToDispatch as $job) {
                    $job->updateToDispatched();

                    // Instantiate the job with properly resolved arguments
                    $jobInstance = self::instantiateJobWithArguments($job->class, $job->arguments);

                    // Check if the instantiated class has the 'coreJobQueue' property
                    if (property_exists($jobInstance, 'coreJobQueue')) {
                        $jobInstance->coreJobQueue = $job;
                    }

                    // Check if the instantiated class has the 'coreJobQueue' property
                    if (property_exists($jobInstance, 'apiJobQueue')) {
                        $jobInstance->apiJobQueue = $job;
                    }

                    // Dispatch the job
                    Queue::pushOn($job->queue, $jobInstance);
                }
            }
        });
    }

    protected static function instantiateJobWithArguments(string $class, array $arguments)
    {
        try {
            $reflectionClass = new ReflectionClass($class);
            $constructor = $reflectionClass->getConstructor();

            if (is_null($constructor)) {
                return new $class;
            }

            $parameters = $constructor->getParameters();
            $resolvedArguments = [];
            $missingArguments = [];

            foreach ($parameters as $parameter) {
                $name = $parameter->getName();

                if (array_key_exists($name, $arguments)) {
                    $resolvedArguments[] = $arguments[$name];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $resolvedArguments[] = $parameter->getDefaultValue();
                } else {
                    $missingArguments[] = $name;
                }
            }

            if (! empty($missingArguments)) {
                throw new InvalidArgumentException(
                    'Missing required arguments: '.implode(', ', $missingArguments)." for class {$class}"
                );
            }

            return $reflectionClass->newInstanceArgs($resolvedArguments);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Failed to instantiate job class {$class}: ".$e->getMessage(), 0, $e);
        }
    }
}
