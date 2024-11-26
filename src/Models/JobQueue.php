<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class JobQueue extends Model
{
    protected $table = 'job_queue';

    protected $guarded = [];

    protected $casts = [
        'arguments' => 'array',
        'extra_data' => 'array',
    ];

    public static function add(
        string $jobClass,
        array $arguments = [],
        ?string $block = null,
        bool $indexed = false,
        ?int $index = null,
        string $queueName = 'default',
        array $extraData = [],
        ?string $dispatchAfter = null
    ) {
        DB::beginTransaction();

        try {
            if (! $block) {
                $block = (string) \Str::uuid();
            }

            // Determine the index value.
            if ($index === null && $indexed) {
                $index = self::where('block_uuid', $block)->max('index') + 1;
            }

            $job = self::create([
                'class' => $jobClass,
                'arguments' => $arguments,
                'extra_data' => $extraData,
                'block_uuid' => $block,
                'index' => $index,
                'status' => 'pending',
                'queue_name' => $queueName,
                'error_message' => null,
                'job_uuid' => (string) \Str::uuid(),
                'sequencial_id' => null,
                'dispatch_after' => $dispatchAfter,
            ]);

            DB::commit();

            return $job;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function dispatch(int $batchSize = 10)
    {
        DB::beginTransaction();

        try {
            // Select a batch of pending jobs where dispatch_after is null or <= now().
            $jobs = self::where('status', 'pending')
                ->where(function ($query) {
                    $query->whereNull('dispatch_after')
                        ->orWhere('dispatch_after', '<=', now());
                })
                ->orderBy('created_at')
                ->limit($batchSize)
                ->lockForUpdate()
                ->get();

            if ($jobs->isEmpty()) {
                DB::commit();

                return null;
            }

            // Mark all selected jobs as 'dispatched'.
            $jobIds = $jobs->pluck('id');
            self::whereIn('id', $jobIds)
                ->where('status', 'pending')
                ->update(['status' => 'dispatched']);

            DB::commit();

            // Dispatch each job in the batch.
            foreach ($jobs as $job) {
                try {
                    $childJob = self::instantiateJobWithArguments($job->class, $job->arguments);
                    $childJob->withJobQueue($job);
                    dispatch($childJob)->onQueue($job->queue_name);
                } catch (\Throwable $e) {
                    // Log the error and update the job status in the database.
                    $job->update([
                        'status' => 'error',
                        'error_message' => $e->getMessage(),
                        'error_stack_trace' => $e->getTraceAsString(),
                        'started_at' => now(),
                        'completed_at' => now(),
                    ]);

                    throw $e;
                }
            }

            return $jobs;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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
                throw new \InvalidArgumentException(
                    'Missing required arguments: '.implode(', ', $missingArguments)." for class {$class}"
                );
            }

            return $reflectionClass->newInstanceArgs($resolvedArguments);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException("Failed to instantiate job class {$class}: ".$e->getMessage(), 0, $e);
        }
    }
}
