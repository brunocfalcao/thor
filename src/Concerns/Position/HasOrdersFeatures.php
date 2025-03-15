<?php

namespace Nidavellir\Thor\Concerns\Position;

trait HasOrdersFeatures
{
    public function profitOrder()
    {
        $this->loadMissing('orders');

        return $this->orders
            ->where('type', 'PROFIT')
            ->whereIn('status', ['NEW', 'PARTIALLY_FILLED'])
            ->first();
    }
}
