<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {

            $table->string('account_canonical')
                ->nullable()
                ->after('name');

            $table->dropUnique('quotes_canonical_unique');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder6::class,
        ]);
    }
};
