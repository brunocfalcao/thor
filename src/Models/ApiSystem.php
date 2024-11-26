<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

/**
 * Defines the many-to-many relationship between
 * ApiSystem and User.
 */
class ApiSystem extends UnguardableModel
{
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
        return $this->hasMany(ExchangeSymbol::class, 'api_system_id');
    }

    public function positions()
    {
        return $this->hasManyThrough(Position::class, Account::class);
    }
}
