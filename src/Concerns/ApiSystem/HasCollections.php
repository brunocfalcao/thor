<?php

namespace Nidavellir\Thor\Concerns\ApiSystem;

trait HasCollections
{
    public static function allExchanges()
    {
        return static::where('is_exchange', true)->get();
    }
}
