<?php

namespace Nidavellir\Thor\Concerns\Account;

trait HasActionFeatures
{
    public function unTrade()
    {
        $this->update(['can_trade' => false]);
    }
}
