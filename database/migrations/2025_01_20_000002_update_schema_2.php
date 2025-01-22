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

            $table->decimal('percentage_gap', 5, 2)
                ->after('closed_at')
                ->default(7.15)
                ->comment('The percentage gap used to determine the spacing between limit orders in the martingale logic');

            $table->unsignedInteger('total_limit_orders')
                ->after('percentage_gap')
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

            $table->decimal('percentage_gap', 5, 2)
                ->after('total_limit_orders')
                ->default(7.5)
                ->comment('The percentage gap used to determine the spacing between limit orders in the martingale logic');
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
