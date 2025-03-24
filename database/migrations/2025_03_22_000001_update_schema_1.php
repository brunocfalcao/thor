<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder12::class,
        ]);
    }
};
