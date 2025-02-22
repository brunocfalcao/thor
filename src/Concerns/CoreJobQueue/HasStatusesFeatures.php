<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

use Illuminate\Support\Carbon;
use Nidavellir\Thor\Models\User;

trait HasStatusesFeatures
{
    public function updateToRunning()
    {
        $this->update([
            'status' => 'running',
            'hostname' => gethostname(),
            'started_at' => now(),
        ]);
    }

    public function updateToCompleted()
    {
        $this->update([
            'hostname' => gethostname(),
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($this->duration > 7500) {
            // Send notification due to low performant core job queue processing.
            User::admin()->get()->each(function ($user) {
                $user->pushover(
                    message: "[{$this->id}] - {$this->class} - Took ".$this->duration.'ms',
                    title: 'Core Job Queue slow performance',
                    applicationKey: 'nidavellir_warnings'
                );
            });
        }
    }

    public function updateToDispatched()
    {
        $this->update([
            'status' => 'dispatched',
        ]);
    }

    public function updateToRetry(Carbon|int|null $retryAfter = null)
    {
        $dispatchAfter = null;

        if ($retryAfter instanceof Carbon) {
            $dispatchAfter = $retryAfter;
        } elseif (is_int($retryAfter)) {
            $dispatchAfter = now()->addSeconds($retryAfter);
        }

        $data = [
            'status' => 'pending',
            'retries' => ++$this->retries,
            'error_message' => null,
            'error_stack_trace' => null,
            'duration' => null,
            'started_at' => null,
            'completed_at' => null,
            'sequencial_id' => null,
            'hostname' => null,
        ];

        if ($dispatchAfter) {
            $data['dispatch_after'] = $dispatchAfter;
        }

        $this->update($data);
    }

    public function updateToRollback()
    {
        $this->update([
            'status' => 'rollback',
        ]);
    }

    public function updateRetries(int $retries)
    {
        $this->update(['retries' => $retries]);
    }

    public function updateToFailed(string|\Throwable $e, $silently = false)
    {
        if (is_string($e)) {
            $errorMessage = $e;
            $traceMessage = null;
        } else {
            $errorMessage = $this->class.' - '.$e->getMessage().' (line '.$e->getLine().')';
            $traceMessage = $e->getTraceAsString();
        }

        // Update the current job to 'failed'.
        $this->update([
            'status' => 'failed',
            'hostname' => gethostname(),
            'error_message' => $errorMessage,
            'error_stack_trace' => $traceMessage,
            'completed_at' => now(),
        ]);

        // Apply "next index" logic.
        if ($this->index) {
            // Cancel all remaining jobs with the same block_uuid and higher index
            $modelClass = get_class($this);
            $modelClass::where('block_uuid', $this->block_uuid)
                ->where('index', '>', $this->index)
                ->where('status', 'pending') // Only cancel jobs still in 'pending' status
                ->update([
                    'status' => 'cancelled',
                ]);
        }

        if (! $silently) {
            // Notify failure via pushover.
            User::where('is_admin', true)
                ->get()
                ->each(function ($user) use ($errorMessage) {
                    $user->pushover($errorMessage, 'Core Job Queue Error', 'nidavellir_errors', ['priority' => 1, 'sound' => 'siren']);
                });
        }
    }
}
