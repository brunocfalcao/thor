<?php

namespace Nidavellir\Thor\Concerns\Order;

trait HasStatusesFeatures
{
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
