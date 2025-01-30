<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_history';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
