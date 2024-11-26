<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Thor\Abstracts\UnguardableModel;

class Trace extends UnguardableModel
{
    // Define the database table associated with this model.
    protected $table = 'traceables';

    // Retrieve the parent related model for this job.
    public function related()
    {
        return $this->morphTo();
    }
}
