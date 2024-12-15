<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $casts = [
        'is_apiable' => 'boolean',
        'is_active' => 'boolean',
        'parameters' => 'array',
    ];

    public function scopeActive(Builder $query)
    {
        $query->where('is_active', true);
    }
}
