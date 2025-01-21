<?php

namespace Nidavellir\Thor\Concerns\Position;

trait HasStatusesFeatures
{
    public function isOpened()
    {
        return $this->status == 'active' || $this->status == 'new';
    }

    public function isRollbacking()
    {
        return $this->status == 'rollbacking';
    }

    public function isClosing()
    {
        return $this->status == 'closing';
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
            $errorMessage = $this->class.' - '.$e->getMessage().' (line '.$e->getLine().')';
            $traceMessage = $e->getTraceAsString();
        }

        $this->update([
            'closed_at' => now(),
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
