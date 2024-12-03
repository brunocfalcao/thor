<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

trait HasRefreshFeatures
{
    public function canBeRefreshed()
    {
        return $this->created_at != $this->updated_at;
    }
}
