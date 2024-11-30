<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Mjolnir\Concerns\Models\Order\HasApiFeatures;
use Nidavellir\Thor\Abstracts\UnguardableModel;
use Nidavellir\Thor\Concerns\Order\HasStatusFeatures;

class Order extends UnguardableModel
{
    use HasApiFeatures;
    use HasStatusFeatures;

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
