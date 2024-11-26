<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class Indicator extends UnguardableModel
{
    protected $casts = [
        'is_apiable' => 'boolean',
        'is_active' => 'boolean',
        'parameters' => 'array',
    ];
}
