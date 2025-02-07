<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class TradingPair extends Model
{
    protected $casts = [
        'exchange_canonicals' => 'array',
    ];

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }
}
