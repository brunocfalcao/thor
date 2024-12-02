<?php

namespace Nidavellir\Thor\Concerns\AnyJobQueue;

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
        $this->update([
            'status' => 'failed',
            'error_message' => $e->getMessage().' (line '.$e->getLine().')',
            'error_stack_trace' => $e->getTraceAsString(),
            'completed_at' => now(),
        ]);
    }
}
