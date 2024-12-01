<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class Trace extends Model
{
    // Define the database table associated with this model.
    protected $table = 'traceables';

    // Retrieve the parent related model for this job.
    public function related()
    {
        return $this->morphTo();
    }
}
