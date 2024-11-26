<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class Symbol extends UnguardableModel
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
