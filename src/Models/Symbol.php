<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Symbol\HasApiFeatures;

class Symbol extends Model
{
    use HasApiFeatures;

    protected $casts = [
        'indicator_last_synced_at' => 'datetime',
    ];

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }
}
