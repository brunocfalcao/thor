<?php

namespace Nidavellir\Thor\Concerns\ExchangeSymbol;

trait HasStatusesFeatures
{
    public function isActive()
    {
        return $this->is_active;
    }

    public function isUpsertable()
    {
        return $this->isActive() && $this->is_upsertable;
    }

    public function isTradeable()
    {
        return $this->isActive() && $this->isUpsertable() && $this->is_tradeable;
    }
}
