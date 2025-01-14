<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBalanceHistory extends Model
{
    protected $table = 'account_balance_history';

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
