<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class TradeConfiguration extends Model
{
    protected $table = 'trade_configuration';

    protected $casts = [
        'is_active' => 'boolean',
        'order_ratios' => 'array',
        'indicators' => 'array',
        'indicator_timeframes' => 'array',
    ];
}
