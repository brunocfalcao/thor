<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Models\Symbol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Nidavellir\Thor\Concerns\ExchangeSymbol\HasStatusesFeatures;
use Nidavellir\Mjolnir\Concerns\Models\ExchangeSymbol\HasApiFeatures;
use Nidavellir\Mjolnir\Concerns\Models\ExchangeSymbol\HasTokenParsingFeatures;

class ExchangeSymbol extends Model
{
    use HasApiFeatures;
    use HasStatusesFeatures;
    use HasTokenParsingFeatures;

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

    public function scopeFromQuote(Builder $query, Quote $quote)
    {
        $query->where('exchange_symbols.quote_id', $quote->id);
    }

    public function scopeEligible(Builder $query)
    {
        // Never make BTC eligible to be selected as an exchange symbol for trading.
        $btcSymbolId = Symbol::query()->firstWhere('token', 'BTC')?->id;

        $query->where('exchange_symbols.is_active', true)
            ->where('exchange_symbols.is_upsertable', true)
            ->where('exchange_symbols.is_tradeable', true)
            ->where('exchange_symbols.symbol_id', '<>', $btcSymbolId)
            ->whereNotNull('direction');
    }
}
