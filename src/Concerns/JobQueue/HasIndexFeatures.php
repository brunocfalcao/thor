<?php

namespace Nidavellir\Thor\Concerns\JobQueue;

use Nidavellir\Thor\Models\JobQueue;

trait HasIndexFeatures
{
    protected function isLast()
    {
        return $this->index == JobQueue::where('block_uuid', $this->block_uuid)->max('index') ||
               $this->index == null;
    }

    protected function isFirst()
    {
        return $this->index == 1 ||
               $this->index == null;
    }
}
