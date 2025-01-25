<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\TradingPair;

class SchemaSeeder3 extends Seeder
{
    public function run(): void
    {
        /**
         * Add a new token: JTO
         */
        TradingPair::create([
            'cmc_id' => 28541,
            'token' => 'JTO',
            'category_canonical' => 'defi',
        ]);
    }
}
