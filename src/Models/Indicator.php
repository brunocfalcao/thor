<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $casts = [
        'is_apiable' => 'boolean',
        'is_active' => 'boolean',
        'parameters' => 'array',
    ];
}
