<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Indicator;

class SchemaSeeder9 extends Seeder
{
    public function run(): void
    {
        Indicator::create([
            'canonical' => 'amplitude-threshold',
            'is_active' => true,
            'class' => "Nidavellir\Mjolnir\Indicators\AmplitudeThresholdIndicator",
            'parameters' => [
                'results' => 2,
                'interval' => '30m',
            ],
        ]);
    }
}
