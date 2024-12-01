<?php

namespace Nidavellir\Thor\Concerns\ApiSystem;

trait HasApiSystemCollections
{
    public static function allExchanges()
    {
        return static::where('is_exchange', false)->get();
    }
}
