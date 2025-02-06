<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\TradingPair;

class SchemaSeeder4 extends Seeder
{
    public function run(): void
    {
        TradingPair::create([
            'cmc_id' => 1321,
            'token' => 'ETC',
            'category_canonical' => 'regular',
        ]);
    }
}
