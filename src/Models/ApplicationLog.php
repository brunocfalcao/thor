<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class ApplicationLog extends UnguardableModel
{
    protected $casts = [
        'debug_backtrace' => 'array',
        'return_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }

    public function exchangeSymbol()
    {
        return $this->belongsTo(ExchangeSymbol::class);
    }

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
