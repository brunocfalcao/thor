<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\System;

class SchemaSeeder2 extends Seeder
{
    public function run(): void
    {
        System::create([
            'can_process_scheduled_tasks' => true,
        ]);
    }
}
