<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

trait HasRefreshFeatures
{
    public function shouldBeRefreshed()
    {
        return $this->retries > 0;
    }
}
