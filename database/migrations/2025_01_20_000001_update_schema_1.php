<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('core_job_queue', function (Blueprint $table) {
            // Index on status and dispatch_after
            $table->index(['status', 'dispatch_after'], 'idx_status_dispatch_after');

            // Index on block_uuid and index
            $table->index(['block_uuid', 'index'], 'idx_block_uuid_index');

            // Index on block_uuid, status, and index
            $table->index(['block_uuid', 'status', 'index'], 'idx_block_uuid_status_index');

            // Index on status, index, and dispatch_after
            $table->index(['status', 'index', 'dispatch_after'], 'idx_status_index_dispatch_after');

            // Index on queue
            $table->index('queue', 'idx_queue');
        });
    }
};
