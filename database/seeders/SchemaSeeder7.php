<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\TradingPair;

class SchemaSeeder7 extends Seeder
{
    public function run(): void
    {
        TradingPair::create([
            'cmc_id' => 10603,
            'token' => 'IMX',
            'category_canonical' => 'pioneer',
        ]);
    }
}
