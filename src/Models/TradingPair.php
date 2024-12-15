<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class TradingPair extends Model
{
    protected $casts = [
        'exchange_canonicals' => 'array',
    ];

    public function baseCurrency()
    {
        return $this->belongsTo(Symbol::class, 'base_currency', 'token');
    }

    public function quoteCurrency()
    {
        return $this->belongsTo(Symbol::class, 'quote_currency', 'token');
    }
}
