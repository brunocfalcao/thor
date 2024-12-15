<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Thor\Concerns\Position\HasStatusesFeatures;
use Nidavellir\Thor\Concerns\Position\HasTokenFeatures;

class Position extends Model
{
    use HasStatusesFeatures;
    use HasTokenFeatures;

    protected $casts = [
        'is_syncing' => 'boolean',
        'is_locked' => 'boolean',
        'order_ratios' => 'array',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
        'indicators' => 'array',
    ];

    public function exchangeSymbol()
    {
        return $this->belongsTo(ExchangeSymbol::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tradeConfiguration()
    {
        return $this->belongsTo(TradeConfiguration::class);
    }

    /**
     * Returns positions that are "in opening" status, meaning new ones
     * (not yet synced) or in 'active' (meaning fully synced).
     */
    public function scopeOpened(Builder $query)
    {
        $query->whereIn('positions.status', ['new', 'active']);
    }
}
