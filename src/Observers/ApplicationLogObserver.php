<?php

namespace Nidavellir\Thor\Observers;

use Illuminate\Support\Str;
use Nidavellir\Thor\Models\ApplicationLog;

class ApplicationLogObserver
{
    public function creating(ApplicationLog $model): void
    {
        if (blank($model->block_uuid)) {
            $model->block_uuid = (string) Str::uuid();
        }
    }
}
