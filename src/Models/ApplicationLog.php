<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $casts = [
        'parameters_array' => 'array',
        'result_array' => 'array',
        'return_data_array' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
