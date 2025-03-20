<?php

namespace Nidavellir\Thor\Database\Seeders;

use Illuminate\Database\Seeder;
use Nidavellir\Thor\Models\Indicator;

class SchemaSeeder11 extends Seeder
{
    public function run(): void
    {
        /**
         * Add indicators for the stop loss reports, and stop loss indicator history data.
         *
         * ADX
         * MFI
         * RSI
         * OBV
         * Bollinger Bands
         */
        Indicator::create([
            'canonical' => 'adx-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\ADXIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'mfi-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\MFIIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'rsi-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\RSIIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'obv-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\OBVIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'bb-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\BBIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);

        Indicator::create([
            'canonical' => 'candle-stop-loss',
            'is_active' => true,
            'type' => 'stop-loss',
            'class' => "Nidavellir\Mjolnir\Indicators\Reporting\CandleIndicator",
            'parameters' => [
                'backtrack' => 1,
                'results' => 2,
            ],
        ]);
    }
}
