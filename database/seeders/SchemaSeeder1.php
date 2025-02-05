<?php

namespace Nidavellir\Thor\Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Nidavellir\Thor\Models\Account;
use Nidavellir\Thor\Models\ApiSystem;
use Nidavellir\Thor\Models\Indicator;
use Nidavellir\Thor\Models\Quote;
use Nidavellir\Thor\Models\TradeConfiguration;
use Nidavellir\Thor\Models\TradingPair;
use Nidavellir\Thor\Models\User;

class SchemaSeeder1 extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        File::put(storage_path('logs/laravel.log'), '');

        Indicator::create([
            'canonical' => 'emas-same-direction',
            'is_active' => true,
            'class' => "Nidavellir\Mjolnir\Indicators\EMAsSameDirection",
            'is_apiable' => false,
        ]);

        Indicator::create([
            'canonical' => 'macd',
            'is_active' => false,
            'class' => "Nidavellir\Mjolnir\Indicators\MACDIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
                'optInFastPeriod' => '12',
                'optInSlowPeriod' => 26,
                'optInSignalPeriod' => 9,
            ],
        ]);

        Indicator::create([
            'canonical' => 'obv',
            'class' => "Nidavellir\Mjolnir\Indicators\OBVIndicator",
            'parameters' => [
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'adx',
            'class' => "Nidavellir\Mjolnir\Indicators\ADXIndicator",
            'parameters' => [
                'results' => 1,
            ],
        ]);

        // Add a new indicator EMAsConvergence.
        Indicator::create([
            'canonical' => 'emas-convergence',
            'is_active' => false,
            'class' => "Nidavellir\Mjolnir\Indicators\EMAsConvergence",
            'is_apiable' => false,
        ]);

        Indicator::create([
            'canonical' => 'ema-40',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
                'period' => '40',
            ],
        ]);

        Indicator::create([
            'canonical' => 'ema-80',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
                'period' => '80',
            ],
        ]);

        Indicator::create([
            'canonical' => 'ema-120',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
                'period' => '120',
            ],
        ]);

        TradeConfiguration::create([
            'is_default' => true,
            'canonical' => 'standard',
            'description' => 'Default 7.5% laddered',
            'indicator_timeframes' => ['1h', '4h', '6h', '12h', '1d'],
            'order_ratios' => [
                [-7.5,  16],
                [-15,  8],
                [-22.5,  4],
                [-30,  2],
            ],

            'profit_percentage' => 0.33,
        ]);

        $admin = User::create([
            'name' => env('ADMIN_USER_NAME'),
            'email' => env('ADMIN_USER_EMAIL'),
            'is_admin' => true,
            'password' => env('ADMIN_USER_PASSWORD'),
            'pushover_key' => env('ADMIN_USER_PUSHOVER_KEY'),
            'is_trader' => false,
        ]);

        Quote::create([
            'canonical' => 'USDT',
            'name' => 'USDT (Tether)',
        ]);

        Quote::create([
            'canonical' => 'USDC',
            'name' => 'USDC (USD Coin)',
        ]);

        // BTC.
        $tradingPairs = [
            ['BTC', '1'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'top20',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Pioneer coins.
        $tradingPairs = [
            ['APT', '21794'],
            ['ARB', '11841'],
            ['ENA', '30171'],
            ['ENS', '13855'],
            ['JTO', '28541'],
            ['JUP', '29210'],
            ['SUI', '20947'],
            ['TAO', '22974'],
            ['TIA', '22861'],
            ['WLD', '13502'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'pioneer',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Regular coins.
        $tradingPairs = [
            ['ALGO', '4030'],
            ['DOT', '6636'],
            ['FET', '3773'],
            ['INJ', '7226'],
            ['POL', '28321'],
            ['QNT', '3155'],
            ['SAND', '6210'],
            ['STX', '4847'],
            ['TRX', '1958'],
            ['VET', '3077'],
            ['XLM', '512'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'regular',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Stable coins.
        $tradingPairs = [
            ['AAVE', '7278'],
            ['ADA', '2010'],
            ['ATOM', '3794'],
            ['AVAX', '5805'],
            ['LINK', '1975'],
            ['SOL', '5426'],
            ['XMR', '328'],
            ['XRP', '52'],
            ['XTZ', '2011'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'stable',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        $binance = ApiSystem::create([
            'name' => 'Binance',
            'canonical' => 'binance',
            'is_exchange' => true,
            'taapi_canonical' => 'binancefutures',
        ]);

        $coinmarketcap = ApiSystem::create([
            'name' => 'CoinmarketCap',
            'canonical' => 'coinmarketcap',
            'is_exchange' => false,
        ]);

        $alternativeMe = ApiSystem::create([
            'name' => 'AlternativeMe',
            'canonical' => 'alternativeme',
            'is_exchange' => false,
        ]);

        $taapi = ApiSystem::create([
            'name' => 'Taapi',
            'canonical' => 'taapi',
            'is_exchange' => false,
        ]);

        $trader = User::create([
            'name' => env('TRADER_NAME'),
            'email' => env('TRADER_EMAIL'),
            'password' => env('TRADER_PASSWORD'),
            'pushover_key' => env('TRADER_PUSHOVER_KEY'),
        ]);

        Account::create([
            'user_id' => $trader->id,
            'api_system_id' => $binance->id,

            'minimum_balance' => 500,
            'max_concurrent_trades' => 12,
            'margin_override' => 100,
            'position_size_percentage' => 2,
            'max_leverage' => 20,
            'negative_pnl_stop_threshold_percentage' => 25,

            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => env('BINANCE_API_KEY'),
                'api_secret' => env('BINANCE_API_SECRET')],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'can_trade' => false,
            'api_system_id' => $binance->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'api_key' => env('BINANCE_API_KEY'),
                'api_secret' => env('BINANCE_API_SECRET')],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'can_trade' => false,
            'api_system_id' => $coinmarketcap->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'api_key' => env('COINMARKETCAP_API_KEY'),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'can_trade' => false,
            'api_system_id' => $taapi->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'secret' => env('TAAPI_SECRET'),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'can_trade' => false,
            'api_system_id' => $alternativeMe->id,
        ]);
    }
}
