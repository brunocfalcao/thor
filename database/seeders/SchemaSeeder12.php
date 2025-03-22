<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Indicator;
use Illuminate\Support\Str;

class SchemaSeeder12 extends Seeder
{
    public function run(): void
    {
        $indicators = Indicator::all();

        foreach ($indicators as $indicator) {
            // Skip the Reporting indicator (the one already in Nidavellir\Mjolnir\Indicators\Reporting\)
            if (Str::startsWith($indicator->class, 'Nidavellir\\Mjolnir\\Indicators\\Reporting\\')) {
                continue;
            }

            // Extract the base class name
            $baseClass = class_basename($indicator->class);

            // Build the new namespaced class under RefreshData
            $indicator->class = "Nidavellir\\Mjolnir\\Indicators\\RefreshData\\{$baseClass}";

            $indicator->save();
        }
    }
}
