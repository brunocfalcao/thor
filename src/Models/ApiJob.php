<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Nidavellir\Thor\Abstracts\UnguardableModel;

class ApiJob extends UnguardableModel
{
    use HasFactory;

    protected $table = 'job_api_queue';

    protected $casts = [
        'parameters' => 'array',
        'response' => 'array',
    ];

    public static function addJob(array $jobData): self
    {
        // Check if block_uuid is passed in the jobData array
        $blockUuid = $jobData['block_uuid'] ?? \Illuminate\Support\Str::uuid()->toString();

        // Return the created job with the block_uuid
        return self::create([
            'class' => $jobData['class'],
            'parameters' => $jobData['parameters'],
            'block_uuid' => $blockUuid,
            'index' => $blockUuid == ($jobData['block_uuid'] ?? null) ? $jobData['index'] ?? null : null,
            'queue_name' => $jobData['queue_name'] ?? 'default',
        ]);
    }

    public static function addJobs(array $jobs, ?string $blockUuid = null)
    {
        $blockUuid = $blockUuid ?? Str::uuid()->toString();

        foreach ($jobs as $jobData) {
            self::addJob($jobData, $blockUuid);
        }
    }

    public static function dispatch()
    {
        DB::beginTransaction();

        try {
            $jobs = self::where('status', 'pending')
                ->where(function ($query) {
                    $query->whereNull('dispatch_after')
                        ->orWhere('dispatch_after', '<=', now());
                })
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get();

            if ($jobs->isEmpty()) {
                DB::commit();

                return null;
            }

            foreach ($jobs as $job) {
                // Check if the job class exists
                if (! class_exists($job->class)) {
                    // Mark the job as failed if the class doesn't exist
                    $job->markAsFailed(null, "Class \"{$job->class}\" not found.", "Class \"{$job->class}\" could not be found.");

                    continue; // Skip this job
                }

                $job->markAsDispatched();
                // If the class exists, dispatch the job
                Queue::pushOn($job->queue_name, new $job->class($job));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPreviousJobs()
    {
        $previousIndex = self::where('block_uuid', $this->block_uuid)
            ->where('index', '<', $this->index)
            ->max('index');

        if ($previousIndex == null) {
            return collect();
        }

        return self::where('block_uuid', $this->block_uuid)
            ->where('index', $previousIndex)
            ->get();
    }

    public function markAsRunning()
    {
        $nextSequentialId = self::max('sequencial_id') + 1;

        $this->update([
            'status' => 'running',
            'sequencial_id' => $nextSequentialId,
            'hostname' => gethostname(),
            'started_at' => now(),
        ]);
    }

    public function markAsDispatched()
    {
        $this->update([
            'status' => 'dispatched',
        ]);
    }

    public function markAsCompleted($response, $duration)
    {
        $this->update([
            'status' => 'completed',
            'response' => $response,
            'duration' => $duration,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(?\Exception $exception, ?string $message = null)
    {
        // If exception is null, just use the provided message
        if ($exception === null) {
            // Use the provided message if it's not null, otherwise use a generic error message
            $errorMessage = $message ?? 'Unknown error occurred.';
            $fullErrorMessage = $errorMessage;
        } else {
            // Use the provided message if it's not null, otherwise fall back to the exception's message
            $errorMessage = $message ?? $exception->getMessage();

            // Get the line number from the exception
            $lineNumber = $exception->getLine();

            // Concatenate the line number to the error message
            $fullErrorMessage = $errorMessage.' (line '.$lineNumber.')';
        }

        // Update the job with the full error details
        $this->update([
            'status' => 'failed',
            'error_message' => $fullErrorMessage,
            'error_stack_trace' => $exception ? $exception->getTraceAsString() : null,
        ]);
    }

    public function resetToPending()
    {
        $this->update([
            'status' => 'pending',
            'sequencial_id' => null,
            'response' => null,
            'started_at' => null,
            'hostname' => null,
        ]);
    }
}
