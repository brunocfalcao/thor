<?php

namespace Nidavellir\Thor\Concerns\Position;

trait HasStatusesFeatures
{
    public function updateToActive()
    {
        $this->update(['status' => 'active']);
    }

    public function updateToClosed()
    {
        $this->update([
            'closed_at' => now(),
            'status' => 'closed',
        ]);
    }
}
