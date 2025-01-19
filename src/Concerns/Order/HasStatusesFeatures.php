<?php

namespace Nidavellir\Thor\Concerns\Order;

trait HasStatusesFeatures
{
    public function updateToFailed(\Throwable $e)
    {
        $this->update([
            'status' => 'FAILED',
            'error_message' => $e->getMessage(),
        ]);
    }
}
