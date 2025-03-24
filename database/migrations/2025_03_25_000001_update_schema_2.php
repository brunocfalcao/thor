<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indicators_history', function (Blueprint $table) {
            $table->string('conclusion')->nullable()->after('indicator_id');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder13::class,
        ]);
    }
};
