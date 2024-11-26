<?php

namespace Nidavellir\Thor\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class UnguardableModel extends Model
{
    protected $guarded = [];
}
