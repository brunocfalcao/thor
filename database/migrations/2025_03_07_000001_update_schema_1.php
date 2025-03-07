<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->text('indicators')
                ->nullable()
                ->after('profit_percentage')
                ->comment('The indicator result at the moment that the position was created');

            $table->string('indicator_timeframe')
                ->nullable()
                ->after('indicators')
                ->comment('The indicator timeframe when the position was created');
        });
    }
};
