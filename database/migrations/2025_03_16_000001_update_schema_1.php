<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->boolean('stop_loss_triggered')
                ->default(false)
                ->after('wap_triggered')
                ->comment('In case a stop loss order is active, at the moment. So we can every hour send reports');
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->string('type')
                ->default('refresh-data')
                ->after('is_apiable')
                ->comment('The indicator group class. E.g.: refresh-data means the indicator will be used to query exchange symbols indicator data cronjobs');
        });

        Schema::create('indicators_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id');
            $table->foreignId('indicator_id');
            $table->json('values');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder11::class,
        ]);
    }
};
