<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'indicator_last_synced_at' => 'datetime',
    ];

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }
}
