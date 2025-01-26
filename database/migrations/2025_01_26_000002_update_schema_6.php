<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Nidavellir\Thor\Models\Account;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::dropIfExists('application_logs');

        Schema::create('application_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loggable_id')->nullable();
            $table->string('loggable_type')->nullable();
            $table->string('block_uuid')->nullable();
            $table->string('action_canonical')->nullable();
            $table->text('action')->nullable();
            $table->text('return_value')->nullable();
            $table->longText('return_data')->nullable();
            $table->text('comments')->nullable();
            $table->json('debug_data')->nullable();
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->uuid()
                ->after('quote_id')
                ->nullable()
                ->comment('Account UUID');
        });

        /**
         * Generate UUIDs for each account.
         */
        Account::all()->each(function ($account) {
            $account->uuid = (string) Str::uuid();
            $account->save();
        });
    }
};
