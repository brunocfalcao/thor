<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nidavellir\Thor\Abstracts\UnguardableModel;

class ExchangeSymbol extends UnguardableModel
{
    use SoftDeletes;

    protected $casts = [
        'is_upsertable' => 'boolean',
        'is_tradeable' => 'boolean',
        'symbol_information' => 'array',
        'leverage_brackets' => 'array',
        'indicators' => 'array',
    ];

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function apiSystem()
    {
        return $this->belongsTo(ApiSystem::class);
    }
}
