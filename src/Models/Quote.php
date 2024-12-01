<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
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
