<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('api_requests_log', function (Blueprint $table) {

            $table->unsignedInteger('duration')
                ->nullable()
                ->after('debug_data');

            $table->timestamp('started_at')
                ->nullable()
                ->after('hostname');

            $table->timestamp('completed_at')
                ->nullable()
                ->after('started_at');
        });
    }
};
