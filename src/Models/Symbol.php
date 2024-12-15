<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Symbol\HasApiFeatures;

class Symbol extends Model
{
    use HasApiFeatures;

    protected $casts = [
        'indicator_last_synced_at' => 'datetime',
        'exchange_canonicals' => 'array',
    ];

    public function getExchangeCanonicalAttribute(ApiSystem $apiSystem)
    {
        if (array_key_exists($apiSystem->canonical, $this->exchange_canonicals)) {
            return $this->exchange_canonicals[$apiSystem->canonical];
        }

        return $this->token;
    }

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }
}
