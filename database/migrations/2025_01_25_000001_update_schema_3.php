<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Index on status and dispatch_after
            $table->index(['id', 'status'], 'idx_id_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Index on status and dispatch_after
            $table->index(['id', 'status', 'type'], 'idx_id_status_type');
        });
    }
};
