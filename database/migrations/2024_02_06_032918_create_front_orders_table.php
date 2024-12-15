<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id') ;
            $table->integer('order_id')->nullable() ;
            $table->integer('wo_id')->nullable() ;
            $table->integer('invad_id') ;
            $table->integer('delad_id') ;
            $table->integer('delivery_id')->nullable() ;
            $table->enum('status',['pending','reject','finish'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_orders');
    }
};
