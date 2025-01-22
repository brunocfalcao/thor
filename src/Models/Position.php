<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Position\HasApiFeatures;
use Nidavellir\Mjolnir\Concerns\Models\Position\HasTokenParsingFeatures;
use Nidavellir\Mjolnir\Concerns\Models\Position\HasWAPFeatures;
use Nidavellir\Thor\Concerns\Position\HasStatusesFeatures;

class Position extends Model
{
    use HasApiFeatures;
    use HasStatusesFeatures;
    use HasTokenParsingFeatures;
    use HasWAPFeatures;

    protected $casts = [
        'wap_triggered' => 'boolean',
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

    public function scopeFromAccount(Builder $query, Account $account)
    {
        $query->where('positions.account_id', $account->id);
    }
}
