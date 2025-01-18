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

        /*
        Indicator::create([
            'canonical' => 'rsi',
            'class' => "Nidavellir\Mjolnir\Indicators\RSIIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
                'period' => '14',
            ],
        ]);
        */

        // Add a new indicator EMAsConvergence.
        Indicator::create([
            'canonical' => 'emas-convergence',
            'is_active' => false,
            'class' => "Nidavellir\Mjolnir\Indicators\EMAsConvergence",
            'is_apiable' => false,
        ]);

        Indicator::create([
            'canonical' => 'ema-80',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'results' => 2,
                'period' => '80',
            ],
        ]);

        Indicator::create([
            'canonical' => 'ema-160',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'results' => 2,
                'period' => '160',
            ],
        ]);

        Indicator::create([
            'canonical' => 'ema-240',
            'class' => "Nidavellir\Mjolnir\Indicators\EMAIndicator",
            'parameters' => [
                'results' => 2,
                'period' => '240',
            ],
        ]);

        TradeConfiguration::create([
            'is_default' => true,
            'canonical' => 'standard',
            'description' => 'Default 7.5% laddered',
            'indicator_timeframes' => ['4h', '6h', '12h', '1d'],
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

        // Meme coins.
        $tradingPairs = [
            ['DOGE', '74'],
            ['SHIB', '5994', '1000SHIB'],
            ['PEPE', '24478', '1000PEPE'],
            ['BONK', '23095', '1000BONK'],
            ['WIF', '28752'],
            ['FLOKI', '10804', '1000FLOKI'],
            ['BRETT', '29743'],
            ['MOG', '27659', '1000000MOG'],
            ['PNUT', '33788'],
            ['POPCAT', '28782'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'meme',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Defi coins.
        $tradingPairs = [
            ['AVAX', '5805'],
            ['LINK', '1975'],
            ['UNI', '7083'],
            ['AAVE', '7278'],
            ['ENA', '30171'],
            ['MKR', '1518'],
            ['STX', '4847'],
            ['INJ', '7226'],
            ['GRT', '6719'],
            ['RUNE', '4157'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'defi',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // AI coins.
        $tradingPairs = [
            ['NEAR', '6535'],
            ['ICP', '8916'],
            ['VIRTUAL', '29420'],
            ['RENDER', '5690'],
            ['TAO', '22974'],
            ['FET', '3773'],
            ['FIL', '2280'],
            ['THETA', '2416'],
            ['GRASS', '32956'],
            ['TURBO', '24911'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'ai',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Gaming coins.
        $tradingPairs = [
            ['IMX', '10603'],
            ['GALA', '7080'],
            ['SAND', '6210'],
            ['EGLD', '6892'],
            ['MANA', '1966'],
            ['AXS', '6783'],
            ['APE', '18876'],
            ['SUPER', '8290'],
            ['NOT', '28850'],
            ['RON', '14101'],
        ];

        foreach ($tradingPairs as $pair) {
            $data = [
                'token' => $pair[0],
                'cmc_id' => $pair[1],
                'category_canonical' => 'gaming',
            ];

            if (array_key_exists(2, $pair)) {
                $data['exchange_canonicals'] = ['binance' => $pair[2]];
            }

            TradingPair::create($data);
        }

        // Top 20 coins.
        $tradingPairs = [
            ['XRP', '52'],
            ['SOL', '5426'],
            ['BNB', '1839'],
            ['ADA', '2010'],
            ['TON', '11419'],
            ['SUI', '20947'],
            ['DOT', '6636'],
            ['HBAR', '4642'],
            ['XLM', '512'],
            ['LTC', '2'],
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

        $karine = User::create([
            'name' => env('KARINE_NAME'),
            'email' => env('KARINE_EMAIL'),
            'password' => env('KARINE_PASSWORD'),
        ]);

        Account::create([
            'user_id' => $karine->id,
            'api_system_id' => $binance->id,

            'is_active' => false,

            'minimum_balance' => 100,
            'max_concurrent_trades' => 1,
            'position_size_percentage' => 2,
            'max_leverage' => 10,
            'negative_pnl_stop_threshold' => 25,

            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => env('KARINE_BINANCE_API_KEY'),
                'api_secret' => env('KARINE_BINANCE_API_SECRET')],
        ]);

        Account::create([
            'user_id' => $trader->id,
            'api_system_id' => $binance->id,

            'minimum_balance' => 500,
            'max_concurrent_trades' => 1,
            'position_size_percentage' => 2,
            'max_leverage' => 20,
            'negative_pnl_stop_threshold' => 15,

            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => env('BINANCE_API_KEY'),
                'api_secret' => env('BINANCE_API_SECRET')],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $binance->id,

            'minimum_balance' => 500,
            'max_concurrent_trades' => 0,
            'position_size_percentage' => 0,
            'max_leverage' => 20,
            'negative_pnl_stop_threshold' => 15,

            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => env('BINANCE_API_KEY'),
                'api_secret' => env('BINANCE_API_SECRET')],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $coinmarketcap->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'api_key' => env('COINMARKETCAP_API_KEY'),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $taapi->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'secret' => env('TAAPI_SECRET'),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $alternativeMe->id,
        ]);
    }
}
