<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('stop_order_threshold_percentage')
                ->default(2)
                ->comment('The percentage to place the stop order, given the current mark price, when all limit orders are filled')
                ->after('minimum_balance');
        });
    }
};
