<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class Order extends UnguardableModel
{
    protected $casts = [
        'is_syncing' => 'boolean',
        'api_result' => 'array',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
