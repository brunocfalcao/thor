<?php

namespace Nidavellir\Thor\Concerns\Order;

trait HasStatusesFeatures
{
    public function changeToSyncing()
    {
        $this->is_syncing = true;
    }

    public function changeToSynced()
    {
        $this->is_syncing = false;
    }
}
