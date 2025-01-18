<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Symbol\HasApiFeatures;
use Nidavellir\Thor\Concerns\Symbol\HasExchangeCanonicalFeatures;

class Symbol extends Model
{
    use HasApiFeatures;
    use HasExchangeCanonicalFeatures;

    protected $casts = [
        'indicator_last_synced_at' => 'datetime',
        'exchange_canonicals' => 'array',
    ];

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }
}
