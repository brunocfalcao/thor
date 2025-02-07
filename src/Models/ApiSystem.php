<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\ApiSystem\HasApiFeatures;
use Nidavellir\Thor\Concerns\ApiSystem\HasCollections;

class ApiSystem extends Model
{
    use HasApiFeatures;
    use HasCollections;

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function rateLimits()
    {
        return $this->hasMany(RateLimit::class);
    }

    public function exchangeSymbols()
    {
        return $this->hasMany(ExchangeSymbol::class);
    }

    public function positions()
    {
        return $this->hasManyThrough(Position::class, Account::class);
    }

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }
}
