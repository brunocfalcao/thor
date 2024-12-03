<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

use Illuminate\Support\Facades\DB;

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
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function updateToDispatched()
    {
        $this->update([
            'status' => 'dispatched',
        ]);
    }

    public function updateToReseted()
    {
        $this->update([
            'status' => 'pending',
            'error_message' => null,
            'error_stack_trace' => null,
            'duration' => null,
            'started_at' => null,
            'completed_at' => null,
            'sequencial_id' => null,
            'hostname' => null,
        ]);
    }

    public function updateToFailed(\Throwable $e)
    {
        DB::transaction(function () use ($e) {
            // Update the current job to 'failed'
            $this->update([
                'status' => 'failed',
                'error_message' => $e->getMessage().' (line '.$e->getLine().')',
                'error_stack_trace' => $e->getTraceAsString(),
                'completed_at' => now(),
            ]);

            // Cancel all remaining jobs with the same block_uuid and higher index
            $modelClass = get_class($this);
            $modelClass::where('block_uuid', $this->block_uuid)
                ->where('index', '>', $this->index)
                ->where('status', 'pending') // Only cancel jobs still in 'pending' status
                ->update([
                    'status' => 'cancelled',
                ]);
        });
    }
}
