<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_configuration', function (Blueprint $table) {
            $table->decimal('magnet_zone_percentage')
                ->after('profit_percentage')
                ->default(0.10)
                ->comment('The upper or lower bound of the limit order price to trigger the magnet');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('magnet_zone_percentage')
                ->after('profit_percentage')
                ->default(0.10)
                ->comment('The upper or lower bound of the limit order price to trigger the magnet');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('magnet_trigger_price', 20, 8)
                ->nullable()
                ->comment('The magnet price that when the price enters this value will trigger the magnet')
                ->after('price');

            $table->boolean('magnet_activated')
                ->default(false)
                ->after('magnet_trigger_price');
        });
    }
};
