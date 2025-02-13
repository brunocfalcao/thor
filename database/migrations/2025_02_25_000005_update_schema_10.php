<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('magnet_trigger_price', 'magnet_activation_price');
            $table->renameColumn('magnet_activated', 'is_magnetized');

            $table->decimal('magnet_trigger_price', 20, 8)
                ->nullable()
                ->comment('The magnet price that when it is reached it will trigger a market order and a limit order cancellation')
                ->after('magnet_activation_price');
        });
    }
};
