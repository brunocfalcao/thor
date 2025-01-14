<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

trait HasDispatchableFeatures
{
    public static function dispatch()
    {
        // Step 1: Handle jobs without an index first
        $jobsWithoutIndex = self::where('status', 'pending')
            ->whereNull('index')
            ->where(function ($query) {
                $query->whereNull('dispatch_after')
                    ->orWhere('dispatch_after', '<=', now());
            })
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

        // Step 2: Handle indexed jobs by block_uuid
        $blocks = self::where('status', 'pending')
            ->whereNotNull('index')
            ->groupBy('block_uuid')
            ->pluck('block_uuid');

        foreach ($blocks as $blockUuid) {
            // Start processing indices for the current block
            $nextIndex = self::where('block_uuid', $blockUuid)
                ->where('status', 'pending')
                ->where(function ($query) {
                    $query->whereNull('dispatch_after')
                        ->orWhere('dispatch_after', '<=', now());
                })
                ->min('index'); // Get the lowest pending index eligible for dispatch

            while ($nextIndex != null) {
                // Check if all jobs in lower indices are completed
                $incompleteJobs = self::where('block_uuid', $blockUuid)
                    ->where('index', '<', $nextIndex)
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->exists();

                if ($incompleteJobs) {
                    break; // Wait until all jobs in previous indices are completed
                }

                // Fetch all jobs in the next index
                $jobsToDispatch = self::where('block_uuid', $blockUuid)
                    ->where('index', $nextIndex)
                    ->where('status', 'pending')
                    ->where(function ($query) {
                        $query->whereNull('dispatch_after')
                            ->orWhere('dispatch_after', '<=', now());
                    })
                    ->get();

                if ($jobsToDispatch->isEmpty()) {
                    info('No jobs to dispatch');
                    break; // No jobs to dispatch for this index
                }

                // Dispatch all jobs for the current index in parallel
                foreach ($jobsToDispatch as $job) {
                    $job->updateToDispatched();

                    // Instantiate the job with properly resolved arguments
                    $jobInstance = self::instantiateJobWithArguments($job->class, $job->arguments);

                    // Attach the job instance to the coreJobQueue
                    $jobInstance->coreJobQueue = $job;

                    if ($job->queue == 'sync') {
                        $jobInstance->handle();
                    } else { // Dispatch the job
                        info('Queueing '.$job->id);
                        Queue::pushOn($job->queue, $jobInstance);
                    }
                }

                // Move to the next index
                $nextIndex = self::where('block_uuid', $blockUuid)
                    ->where('status', 'pending')
                    ->where(function ($query) {
                        $query->whereNull('dispatch_after')
                            ->orWhere('dispatch_after', '<=', now());
                    })
                    ->min('index');
            }
        }
    }

    protected static function instantiateJobWithArguments(string $class, ?array $arguments)
    {
        try {
            $arguments = $arguments ?? []; // Default to an empty array if $arguments is null
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
