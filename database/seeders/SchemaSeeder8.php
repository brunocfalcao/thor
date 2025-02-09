<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Account;

class SchemaSeeder8 extends Seeder
{
    public function run(): void
    {
        Account::where('can_trade', false)->update([
            'profit_percentage' => 0,
            'stop_order_threshold_percentage' => 0,
            'with_half_positions_direction' => false,
            'margin_ratio_notification_threshold' => 0,
        ]);
    }
}
