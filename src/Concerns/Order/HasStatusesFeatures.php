<?php

namespace Nidavellir\Thor\Concerns\Order;

trait HasStatusesFeatures
{
    public function changeToFailed(\Throwable $e)
    {
        $this->update([
            'status' => 'FAILED',
            'error_message' => $e->getMessage(),
        ]);
    }
}
