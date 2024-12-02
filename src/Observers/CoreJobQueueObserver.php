<?php

namespace Nidavellir\Thor\Observers;

use Illuminate\Support\Str;
use Nidavellir\Thor\Models\CoreJobQueue;

class CoreJobQueueObserver
{
    public function creating(CoreJobQueue $model): void
    {
        if (blank($model->block_uuid)) {
            $model->block_uuid = (string) Str::uuid();
        }

        if (blank($model->job_uuid)) {
            $model->job_uuid = (string) Str::uuid();
        }
    }
}
