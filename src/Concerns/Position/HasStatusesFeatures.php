<?php

namespace Nidavellir\Thor\Concerns\Position;

trait HasStatusesFeatures
{
    public function isActive()
    {
        return ! in_array($this->status, ['closed', 'failed', 'cancelled', 'rollbacked']);
    }

    public function isRollbacking()
    {
        return $this->status == 'rollbacking';
    }

    public function isClosing()
    {
        return $this->status == 'closing';
    }

    public function isCancelled()
    {
        return $this->status == 'cancelled';
    }

    public function isRollbacked()
    {
        return $this->status == 'rollbacked';
    }

    public function updateToActive()
    {
        $this->update(['status' => 'active']);
    }

    public function updateToRollbacking(?string $message = null)
    {
        $this->update([
            'status' => 'rollbacking',
            'error_message' => $message,
        ]);
    }

    public function updateToRollbacked(?string $message = null)
    {
        $this->update([
            'closed_at' => now(),
            'status' => 'rollbacked',
            'error_message' => $message,
        ]);
    }

    public function updateToClosing()
    {
        $this->update([
            'status' => 'closing',
        ]);
    }

    public function updateToClosed()
    {
        $this->update([
            'closed_at' => now(),
            'status' => 'closed',
        ]);
    }

    public function updateToFailed(string|\Throwable $e)
    {
        if (is_string($e)) {
            $errorMessage = $e;
            $traceMessage = null;
        } else {
            $errorMessage = $e->getMessage().' (line '.$e->getLine().')';
            $traceMessage = $e->getTraceAsString();
        }

        $this->update([
            'closed_at' => now(),
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function updateToCancelled(string|\Throwable $e)
    {
        if (is_string($e)) {
            $errorMessage = $e;
            $traceMessage = null;
        } else {
            $errorMessage = $e->getMessage().' (line '.$e->getLine().')';
            $traceMessage = $e->getTraceAsString();
        }

        $this->update([
            'closed_at' => now(),
            'status' => 'cancelled',
            'error_message' => $errorMessage,
        ]);
    }
}
