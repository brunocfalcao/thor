<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\TradeConfiguration\HasApiFeatures;

class TradeConfiguration extends Model
{
    use HasApiFeatures;

    protected $table = 'trade_configuration';

    protected $casts = [
        'is_default' => 'boolean',
        'order_ratios' => 'array',
        'indicators' => 'array',
        'indicator_timeframes' => 'array',
    ];

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }

    public function scopeDefault(Builder $query)
    {
        $query->where('is_default', true);
    }
}
