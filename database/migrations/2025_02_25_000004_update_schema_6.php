<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedInteger('stop_order_trigger_duration_minutes')
                  ->after('minimum_balance')
                  ->default(120)
                  ->comment('Number of minutes that will wait until a stop market order is placed, when all limit orders are filled on a position');
        });
    }
};
