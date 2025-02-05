<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update the 'positions' table
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('order_ratios');

            $table->unsignedInteger('total_limit_orders')
                ->after('closed_at')
                ->default(4)
                ->comment('Total limit orders, for the martingale calculation');
        });

        // Update the 'trade_configuration' table
        Schema::table('trade_configuration', function (Blueprint $table) {
            $table->dropColumn('order_ratios');

            $table->unsignedInteger('total_limit_orders')
                ->after('canonical')
                ->default(4)
                ->comment('Total limit orders, for the martingale calculation');

            $table->decimal('percentage_gap_long', 5, 2)
                ->after('total_limit_orders')
                ->default(7.5)
                ->comment('Order limit laddered percentage gaps used when the position is a LONG');

            $table->decimal('percentage_gap_short', 5, 2)
                ->after('total_limit_orders')
                ->default(8.5)
                ->comment('Order limit laddered percentage gaps used when the position is a SHORT');
        });

        Schema::create('system', function (Blueprint $table) {
            $table->id();

            $table->boolean('can_process_scheduled_tasks')
                ->default(true);

            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder2::class,
        ]);
    }
};
