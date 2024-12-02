<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Order\HasApiFeatures;
use Nidavellir\Thor\Concerns\Order\HasStatusesFeatures;

class Order extends Model
{
    use HasApiFeatures;
    use HasStatusesFeatures;

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
