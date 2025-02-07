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
        'indicator_timeframes' => 'array',
        'order_ratios' => 'array',
    ];

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }

    public function scopeDefault(Builder $query)
    {
        $query->where('is_default', true);
    }

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }
}
