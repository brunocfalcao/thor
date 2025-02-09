<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->renameColumn('max_leverage', 'max_leverage_long');

            $table->unsignedInteger('max_leverage_short')->default(15)
                ->after('max_leverage_long')
                ->comment('The max leverage for short positions');
        });
    }
};
