<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('core_job_queue', function (Blueprint $table) {
            $table->boolean('was_notified')
                ->default(false)
                ->after('error_stack_trace')
                ->comment('If this core job queue entry already notified the admins');
        });
    }
};
