<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'system';

    protected $casts = [
        'can_process_scheduled_tasks' => 'boolean',
    ];

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }
}
