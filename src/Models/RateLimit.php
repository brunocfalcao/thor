<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class RateLimit extends Model
{
    protected $casts = [
        'retry_after' => 'datetime',
    ];

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }
}
