<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_configuration', function (Blueprint $table) {
            $table->unsignedInteger('least_changing_timeframe_index')
                  ->default(1)
                  ->comment('Minimum index on the timeframe array to accept a direction change');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder7::class,
        ]);
    }

    public function down(): void
    {
    }
};
