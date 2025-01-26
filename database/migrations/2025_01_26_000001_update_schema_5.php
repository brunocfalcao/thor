<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('skip_observer')
                ->after('side')
                ->default(false)
                ->comment('In case the next observer call can be skipped for this order id, so we avoid observer trigger endless loops');
        });
    }
};
