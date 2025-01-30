<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::create('order_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->string('orderId')->nullable();
            $table->string('symbol')->nullable();
            $table->string('status')->nullable();
            $table->string('clientOrderId')->nullable();
            $table->string('price')->nullable();
            $table->string('avgPrice')->nullable();
            $table->string('origQty')->nullable();
            $table->string('executedQty')->nullable();
            $table->string('cumQuote')->nullable();
            $table->string('timeInForce')->nullable();
            $table->string('type')->nullable();
            $table->string('reduceOnly')->nullable();
            $table->string('closePosition')->nullable();
            $table->string('side')->nullable();
            $table->string('positionSide')->nullable();
            $table->string('stopPrice')->nullable();
            $table->string('workingType')->nullable();
            $table->string('priceProtect')->nullable();
            $table->string('origType')->nullable();
            $table->string('priceMatch')->nullable();
            $table->string('selfTradePreventionMode')->nullable();
            $table->string('goodTillDate')->nullable();
            $table->string('time')->nullable();
            $table->string('updateTime')->nullable();
            $table->timestamps();
        });
    }
};
