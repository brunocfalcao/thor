<?php

namespace Nidavellir\Thor\Concerns\Symbol;

use Nidavellir\Thor\Models\ApiSystem;

trait HasExchangeCanonicalFeatures
{
    public function getExchangeCanonicalAttribute(ApiSystem $apiSystem)
    {
        if (array_key_exists($apiSystem->canonical, $this->exchange_canonicals)) {
            return $this->exchange_canonicals[$apiSystem->canonical];
        }

        return $this->token;
    }
}
