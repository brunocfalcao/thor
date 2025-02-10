<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedInteger('filled_orders_to_notify')
                ->after('minimum_balance')
                ->default(2)
                ->comment('Total filled orders prior to send a WAP notification');
        });
    }
};
