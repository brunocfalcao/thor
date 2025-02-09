<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_configuration', function (Blueprint $table) {
            $table->unsignedInteger('least_changing_timeframe_index')
                ->after('profit_percentage')
                ->default(1)
                ->comment('Minimum index on the timeframe array to accept a direction change. E.g.: 1 = 4h');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder7::class,
        ]);
    }

    public function down(): void {}
};
