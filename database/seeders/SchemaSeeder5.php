<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\User;

class SchemaSeeder5 extends Seeder
{
    public function run(): void
    {
        $yev = User::create([
            'name' => 'Yev Ledenov',
            'email' => 'yev.ledenov@yahoo.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_admin' => false,
            'is_trader' => true,
        ]);
    }
}
