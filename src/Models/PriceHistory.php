<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $table = 'price_history';

    public function exchangeSymbol()
    {
        return $this->belongsTo(ExchangeSymbol::class);
    }

    public function logs()
    {
        return $this->morphMany(ApplicationLog::class, 'loggable');
    }
}
