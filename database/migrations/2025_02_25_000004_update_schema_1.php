<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_logs', function (Blueprint $table) {
            $table->dropColumn([
                'action',
                'return_value',
                'return_data',
                'comments',
                'debug_data',
            ]);

            $table->json('parameters_array')
                ->nullable();

            $table->json('result_array')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->longText('return_data_text')
                ->nullable();

            $table->json('return_data_array')
                ->nullable();
        });
    }

    public function down(): void {}
};
