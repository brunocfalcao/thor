<?php

namespace Nidavellir\Thor\Concerns\JobQueue;

trait HasStatusesFeatures
{
    protected function updateToRunning()
    {
        $this->update([
            'status' => 'running',
            'hostname' => gethostname(),
        ]);

        $this->startDuration();
    }

    protected function updateToCompleted()
    {
        $this->update([
            'status' => 'completed',
        ]);

        $this->finalizeDuration();
    }

    protected function updateToDispatched()
    {
        $this->update([
            'status' => 'dispatched',
        ]);

        $this->finalizeDuration();
    }

    protected function updateToReseted()
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
        ]);

        $this->finalizeDuration();
    }
}
