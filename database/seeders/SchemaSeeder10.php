<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\ExchangeSymbol;
use Nidavellir\Thor\Models\TradingPair;

class SchemaSeeder10 extends Seeder
{
    public function run(): void
    {
        /**
         * Add pairs BNB, DOGE, BCH, LTC, UNI, NEO.
         *
         * Just keep active:
         * XRP, BNB, SOL, ADA, DOGE, LINK, XLM, AVAX, SUI, BCH, LTC, UNI, NEO.
         *
         * Deactivate the others.
         */
        TradingPair::create([
            'cmc_id' => 1839,
            'token' => 'BNB',
            'category_canonical' => 'regular',
        ]);

        TradingPair::create([
            'cmc_id' => 74,
            'token' => 'DOGE',
            'category_canonical' => 'regular',
        ]);

        TradingPair::create([
            'cmc_id' => 1831,
            'token' => 'BCH',
            'category_canonical' => 'regular',
        ]);

        TradingPair::create([
            'cmc_id' => 2,
            'token' => 'LTC',
            'category_canonical' => 'regular',
        ]);

        TradingPair::create([
            'cmc_id' => 7083,
            'token' => 'UNI',
            'category_canonical' => 'regular',
        ]);

        TradingPair::create([
            'cmc_id' => 1376,
            'token' => 'NEO',
            'category_canonical' => 'regular',
        ]);

        /**
         * Disable all tokens except:
         *
         * XRP, BNB, SOL, ADA, DOGE, LINK, XLM, AVAX, SUI, BCH, LTC, UNI, NEO
         */
        ExchangeSymbol::query()->update(['is_tradeable' => false, 'is_upsertable' => false]);

        $symbols = ['XRP', 'BNB', 'SOL', 'ADA', 'DOGE', 'LINK', 'XLM', 'AVAX', 'BCH', 'LTC', 'UNI', 'NEO'];

        ExchangeSymbol::where(function ($query) use ($symbols) {
            foreach ($symbols as $symbol) {
                $query->orWhere('symbol_information', 'like', "%{$symbol}USDT%");
            }
        })->update([
            'is_tradeable' => 1,
            'is_upsertable' => 1,
        ]);
    }
}
