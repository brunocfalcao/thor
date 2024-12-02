<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Thor\Concerns\AnyJobQueue\HasDispatchableFeatures;
use Nidavellir\Thor\Concerns\AnyJobQueue\HasIndexFeatures;
use Nidavellir\Thor\Concerns\AnyJobQueue\HasStatusesFeatures;
use Nidavellir\Thor\Concerns\AnyJobQueue\HasTimerFeatures;
use Nidavellir\Thor\Concerns\AnyJobQueue\HasUuidFeatures;

class CoreJobQueue extends Model
{
    use HasDispatchableFeatures;
    use HasIndexFeatures;
    use HasStatusesFeatures;
    use HasTimerFeatures;
    use HasUuidFeatures;

    protected $table = 'core_job_queue';

    protected $casts = [
        'arguments' => 'array',
        'extra_data' => 'array',
        'response' => 'array',
    ];

    public function ApiJobQueues()
    {
        return $this->hasMany(ApiJobQueue::class);
    }
}
