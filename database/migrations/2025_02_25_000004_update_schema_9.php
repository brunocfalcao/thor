<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->decimal('last_mark_price', 20, 8)
                ->nullable()
                ->comment('Last mark price fetched from the exchange symbol')
                ->after('closing_price');
        });
    }
};
