<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Indicator;

class SchemaSeeder13 extends Seeder
{
    public function run(): void
    {
        Indicator::where('canonical', 'bb-stop-loss')->forceDelete();

        Indicator::create([
            'canonical' => 'macd-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\MACDIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);
    }
}
