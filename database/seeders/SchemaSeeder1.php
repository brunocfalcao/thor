<?php

namespace Nidavellir\Thor\Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nidavellir\Thor\Models\Account;
use Nidavellir\Thor\Models\ApiSystem;
use Nidavellir\Thor\Models\ExchangeSymbol;
use Nidavellir\Thor\Models\Indicator;
use Nidavellir\Thor\Models\Order;
use Nidavellir\Thor\Models\Position;
use Nidavellir\Thor\Models\Quote;
use Nidavellir\Thor\Models\Symbol;
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

        $indicator_timeframes = config('excalibur.apis.taapi.timeframes');

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

        TradeConfiguration::create([
            'is_active' => true,
            'canonical' => 'standard',
            'description' => 'average limit orders + final at 31.45',
            'negative_pnl_stop_threshold' => 3.5,
            'max_concurrent_trades' => 6,
            'minimum_margin' => 50,
            'position_size_percentage' => 6.5,
            'max_leverage_ratio' => 20,
            'order_ratios' => [
                'MARKET' => [0, 32],

                'LIMIT' => [
                    [-8,  16],
                    [-16,  8],
                    [-24,  4],
                    [-32,  2],
                ],

                'PROFIT' => [0.33, 1],
            ],
        ]);

        TradeConfiguration::create([
            'is_active' => false,
            'canonical' => 'testing',
            'description' => 'Trade configuration for testing purposes',
            'negative_pnl_stop_threshold' => 3.5,
            'max_concurrent_trades' => 2,
            'minimum_margin' => 40,
            'position_size_percentage' => 6.5,
            'max_leverage_ratio' => 20,
            'order_ratios' => [
                'MARKET' => [0, 32],

                'LIMIT' => [
                    [-0.15,  16],
                    [-16.07,  8],
                    [-21.37,  4],
                    [-29.953,  2],
                ],

                'PROFIT' => [0.15, 1],
            ],
        ]);

        $admin = User::create([
            'name' => env('ADMIN_USER_NAME'),
            'email' => env('ADMIN_USER_EMAIL'),
            'is_active_overrided' => true,
            'is_admin' => true,
            'password' => env('ADMIN_USER_PASSWORD'),
            'pushover_key' => Crypt::encrypt(env('ADMIN_USER_PUSHOVER_KEY')),
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

        $tradingPairs = [
            ['SOL', 5426],
            ['DOGE', 74],
            ['XRP', 52],
            ['ADA', 2010],
            ['TON', 11419],
            ['AVAX', 5805],
            ['LINK', 1975],
            ['UNI', 7083],
            ['AAVE', 7278],
            ['NEO', 1376],
            ['FIL', 2280],
            ['TIA', 22861],
            ['IMX', 10603],
            ['QNT', 3155],
            ['GALA', 7080],
            ['JASMY', 8425],
        ];

        foreach ($tradingPairs as $pair) {
            TradingPair::create([
                'token' => $pair[0],
                'cmc_id' => $pair[1]]);
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
            'is_active_overrided' => true,
            'password' => env('TRADER_PASSWORD'),
            'pushover_key' => Crypt::encrypt(env('TRADER_PUSHOVER_KEY')),
        ]);

        Account::create([
            'user_id' => $trader->id,
            'api_system_id' => $binance->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => Crypt::encrypt(env('BINANCE_API_KEY')),
                'api_secret' => Crypt::encrypt(env('BINANCE_API_SECRET'))],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $binance->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => Crypt::encrypt(env('BINANCE_API_KEY')),
                'api_secret' => Crypt::encrypt(env('BINANCE_API_SECRET'))],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $coinmarketcap->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 0,
            'credentials' => [
                'api_key' => Crypt::encrypt(env('COINMARKETCAP_API_KEY')),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $taapi->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 0,
            'credentials' => [
                'secret' => Crypt::encrypt(env('TAAPI_SECRET')),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $alternativeMe->id,
            'max_balance_percentage' => 0,
        ]);

        // Stubs.
        // Insert Order stub.
        $order = Order::create([
            'position_id' => 1,
            'uuid' => (string) Str::uuid(),
            'type' => 'MARKET',
            'status' => 'FILLED',
            'quantity' => 2,
            'price' => 10,
            'side' => 'BUY',
            'quantity' => 1,
            'exchange_order_id' => 29917820287,
        ]);

        $position = Position::create([
            'trade_configuration_id' => 4,
            'account_id' => 1,
            'exchange_symbol_id' => 1,
            'status' => 'closed',
            'direction' => 'LONG',
        ]);

        Symbol::create([
            'cmc_id' => 2280,
            'name' => 'Filecoin',
            'token' => 'FIL',
        ]);

        ExchangeSymbol::create([
            'symbol_id' => 1,
            'quote_id' => 1,
            'api_system_id' => 1,
            'price_precision' => 3,
            'quantity_precision' => 1,
            'tick_size' => 0.001,
        ]);
    }
}
