<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nidavellir\Mjolnir\Concerns\Models\Account\HasApiFeatures;

class Account extends Model
{
    use HasApiFeatures, SoftDeletes;

    protected $casts = [
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
}
