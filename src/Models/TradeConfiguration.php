<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class TradeConfiguration extends UnguardableModel
{
    protected $table = 'trade_configuration';

    protected $casts = [
        'is_active' => 'boolean',
        'order_ratios' => 'array',
        'indicators' => 'array',
        'indicator_timeframes' => 'array',
    ];
}
