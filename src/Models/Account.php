<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nidavellir\Mjolnir\Concerns\Models\Account\HasApiFeatures;
use Nidavellir\Thor\Concerns\Account\HasActionFeatures;
use Nidavellir\Thor\Concerns\Account\HasDrawDownFeatures;

class Account extends Model
{
    use HasActionFeatures, HasApiFeatures, HasDrawDownFeatures, SoftDeletes;

    protected $casts = [
        'should_notify' => 'boolean',
        'follow_btc_direction' => 'boolean',
        'with_half_positions_direction' => 'boolean',
        'is_active' => 'boolean',
        'can_trade' => 'boolean',
        'credentials' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function rateLimits()
    {
        return $this->hasMany(RateLimit::class);
    }

    public function balanceHistory()
    {
        return $this->hasMany(AccountBalanceHistory::class);
    }

    public function scopeActive(Builder $query)
    {
        $query->where('accounts.is_active', true);
    }

    public function scopeCanTrade(Builder $query)
    {
        $query->active()->where('accounts.can_trade', true);
    }
}
