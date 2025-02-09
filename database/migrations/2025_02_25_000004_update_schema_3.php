<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('profit_percentage', 5, 3)
                ->after('minimum_balance')
                ->default(0.467)
                ->comment('Minimum profit percentage, copied from the default trade configuration');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder8::class,
        ]);
    }
};
