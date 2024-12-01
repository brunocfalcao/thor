<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Thor\Concerns\JobQueue\HasIndexFeatures;
use Nidavellir\Thor\Concerns\JobQueue\HasTimerFeatures;
use Nidavellir\Thor\Concerns\JobQueue\HasStatusesFeatures;

class JobQueue extends Model
{
    use HasIndexFeatures;
    use HasStatusesFeatures;
    use HasTimerFeatures;

    protected $table = 'job_block_queue';

    protected $casts = [
        'arguments' => 'array',
        'extra_data' => 'array',
    ];
}
