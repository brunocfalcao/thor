<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class RateLimit extends UnguardableModel
{
    protected $casts = [
        'retry_after' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }
}
