<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasDispatchableFeatures;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasIndexFeatures;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasRefreshFeatures;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasStatusesFeatures;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasTimerFeatures;
use Nidavellir\Thor\Concerns\CoreJobQueue\HasUuidFeatures;

class CoreJobQueue extends Model
{
    use HasDispatchableFeatures;
    use HasIndexFeatures;
    use HasRefreshFeatures;
    use HasStatusesFeatures;
    use HasTimerFeatures;
    use HasUuidFeatures;

    protected $table = 'core_job_queue';

    protected $casts = [
        'arguments' => 'array',
        'extra_data' => 'array',
        'response' => 'array',
    ];
}
