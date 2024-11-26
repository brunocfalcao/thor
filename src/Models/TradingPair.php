<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class TradingPair extends UnguardableModel
{
    public function baseCurrency()
    {
        return $this->belongsTo(Symbol::class, 'base_currency', 'token');
    }

    public function quoteCurrency()
    {
        return $this->belongsTo(Symbol::class, 'quote_currency', 'token');
    }
}
