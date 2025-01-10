<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\ExchangeSymbol\HasApiFeatures;

class ExchangeSymbol extends Model
{
    use HasApiFeatures;

    protected $casts = [
        'is_active' => 'boolean',
        'is_upsertable' => 'boolean',
        'is_tradeable' => 'boolean',
        'symbol_information' => 'array',
        'leverage_brackets' => 'array',
        'indicators' => 'array',
    ];

    public function tradeConfiguration()
    {
        return $this->belongsTo(TradeConfiguration::class);
    }

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }

    public function scopeTradeable(Builder $query)
    {
        $query->where('exchange_symbols.is_active', true)
            ->where('exchange_symbols.is_upsertable', true)
            ->where('exchange_symbols.is_tradeable', true);
    }
}
