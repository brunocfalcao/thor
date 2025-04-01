<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('should_notify')
                ->default(true)
                ->comment('Should send pushover notifications (warnings and others), errors are always sent to admins')
                ->after('uuid');
        });
    }
};
