<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorHistory extends Model
{
    protected $table = 'indicators_history';

    protected $casts = [
        'values' => 'array',
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
