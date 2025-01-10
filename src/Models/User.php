<?php

namespace Nidavellir\Thor\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nidavellir\Thor\Concerns\User\HasNotificationFeatures;

class User extends Authenticatable
{
    use HasNotificationFeatures;
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'binance_api_key' => 'encrypted',
        'binance_secret_key' => 'encrypted',

        'email_verified_at' => 'datetime',

        'is_trader' => 'boolean',
        'is_active' => 'boolean',
        'is_admin' => 'boolean',

        'password' => 'hashed',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function positions()
    {
        return $this->hasManyThrough(Position::class, Account::class);
    }

    public function rateLimits()
    {
        return $this->hasManyThrough(RateLimit::class, Account::class);
    }
}
