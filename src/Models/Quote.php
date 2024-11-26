<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class Quote extends UnguardableModel
{
    public $timestamps = false;

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function positions()
    {
        return $this->hasManyThrough(Position::class, ExchangeSymbol::class, 'quote_id', 'exchange_symbol_id', 'id', 'id');
    }
}
