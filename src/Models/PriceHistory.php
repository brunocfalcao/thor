<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class PriceHistory extends UnguardableModel
{
    protected $table = 'price_history';

    public function exchangeSymbol()
    {
        return $this->belongsTo(ExchangeSymbol::class);
    }
}
