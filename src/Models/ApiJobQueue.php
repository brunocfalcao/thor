<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiJobQueue extends Model
{
    use HasFactory;

    protected $table = 'api_job_queue';

    protected $casts = [
        'arguments' => 'array',
        'extra_data' => 'array',
        'response' => 'array',
    ];

    public function CoreJobQueue()
    {
        return $this->belongsTo(CoreJobQueue::class);
    }
}
