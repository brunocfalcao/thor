<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Quote;

class SchemaSeeder6 extends Seeder
{
    public function run(): void
    {
        Quote::create([
            'canonical' => 'USDC',
            'name' => 'USDC (USD Coin)',
            'account_canonical' => 'BNFCR',
        ]);

        Quote::find(1)->update(['account_canonical' => 'USDT']);
        Quote::find(2)->update(['account_canonical' => 'USDC']);
    }
}
