<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeSymbol extends Model
{
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
}
