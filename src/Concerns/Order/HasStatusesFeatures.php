<?php

namespace Nidavellir\Thor\Concerns\Order;

trait HasStatusesFeatures
{
    public function updateToCancelled(?string $message = null)
    {
        $this->update(['status' => 'CANCELLED', 'error_message' => $message]);
    }

    public function updateToInvalid(?string $message = null)
    {
        $this->update(['status' => 'INVALID', 'error_message' => $message]);
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
            'status' => 'FAILED',
            'error_message' => $errorMessage,
        ]);
    }
}
