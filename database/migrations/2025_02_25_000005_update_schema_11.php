<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('is_magnetized', 'magnet_status');

            $table->string('magnet_status')
                ->nullable()
                ->comment('The magnet status: inactive, activated, triggering, triggered, cancelled')
                ->change();
        });
    }
};
