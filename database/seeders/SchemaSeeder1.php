<?php

namespace Nidavellir\Thor\Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nidavellir\Thor\Models\Account;
use Nidavellir\Thor\Models\ApiSystem;
use Nidavellir\Thor\Models\Indicator;
use Nidavellir\Thor\Models\Order;
use Nidavellir\Thor\Models\Position;
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

        $indicator_timeframes = config('excalibur.apis.taapi.timeframes');

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
            'description' => 'average limit orders + final at 31.45',
            'indicator_timeframes' => ['1h', '4h', '6h', '12h', '1d'],
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
            'is_default' => false,
            'canonical' => 'testing',
            'description' => 'Trade configuration for testing purposes',
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
            ['ICP', '8916'],
            ['AAVE', '7278'],
            ['XLM', '512'],
            ['RENDER', '5690'],
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
            'is_active_overrided' => true,
            'password' => env('TRADER_PASSWORD'),
            'pushover_key' => Crypt::encrypt(env('TRADER_PUSHOVER_KEY')),
        ]);

        Account::create([
            'user_id' => $trader->id,
            'api_system_id' => $binance->id,

            'minimum_margin' => 1,
            'max_concurrent_trades' => 6,
            'position_size_percentage' => 5,
            'max_margin_ratio' => 20,
            'negative_pnl_stop_threshold' => 15,

            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'max_balance_percentage' => 75,
            'credentials' => [
                'api_key' => Crypt::encrypt(env('BINANCE_API_KEY')),
                'api_secret' => Crypt::encrypt(env('BINANCE_API_SECRET'))],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $binance->id,

            'minimum_margin' => 1,
            'max_concurrent_trades' => 6,
            'position_size_percentage' => 5,
            'max_margin_ratio' => 20,
            'negative_pnl_stop_threshold' => 15,

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
            'credentials' => [
                'api_key' => Crypt::encrypt(env('COINMARKETCAP_API_KEY')),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $taapi->id,
            'quote_id' => Quote::firstWhere('canonical', 'USDT')->id,
            'credentials' => [
                'secret' => Crypt::encrypt(env('TAAPI_SECRET')),
            ],
        ]);

        Account::create([
            'user_id' => $admin->id,
            'api_system_id' => $alternativeMe->id,
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
    }
}
