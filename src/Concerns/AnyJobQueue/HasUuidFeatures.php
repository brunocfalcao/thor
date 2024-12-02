<?php

namespace Nidavellir\Thor\Concerns\AnyJobQueue;

use Illuminate\Support\Str;

trait HasUuidFeatures
{
    public function generateBlockUuid()
    {
        $this->block_uuid = (string) Str::uuid();
    }

    public function generateJobUuid()
    {
        $this->job_uuid = (string) Str::uuid();
    }
}
