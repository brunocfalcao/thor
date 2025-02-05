<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\TradeConfiguration;

class SchemaSeeder3 extends Seeder
{
    public function run(): void
    {
        TradeConfiguration::query()->default()->first()->update(['total_limit_orders' => 5]);
    }
}
