<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('core_job_queue', function (Blueprint $table) {
            $table->boolean('max_retries_reached')
                ->default(false)
                ->after('retries')
                ->comment('In case this core job queue has achieved the max retries, and failed after');
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder10::class,
        ]);
    }
};
