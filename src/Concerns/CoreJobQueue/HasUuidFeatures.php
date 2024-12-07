<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

use Illuminate\Support\Str;

trait HasUuidFeatures
{
    public static function newUuid()
    {
        return (string) Str::uuid();
    }
}
