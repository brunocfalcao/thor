<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('margin_ratio_notification_threshold', 5, 2)
                ->after('margin_override')
                ->default(2)
                ->comment('Margin ratio limit to start notifying the account admins');
        });
    }
};
