<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->renameColumn('position_size_percentage', 'position_size_percentage_long');

            $table->decimal('position_size_percentage_short', 5, 2)
                ->after('position_size_percentage_long')
                ->default(4)
                ->comment('The margin percentage that will be used on short positions');
        });
    }
};
