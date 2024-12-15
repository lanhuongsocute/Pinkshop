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
        Schema::create('warehouse_in_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id')->default(0) ;
            $table->enum('doc_type',['wi','mi','ui','ti','pi','ic'])->default('wi');
            $table->unsignedBigInteger('wh_id')->default(0) ;
            $table->unsignedBigInteger('product_id') ;
            $table->integer('quantity');
            $table->integer('prebalance')->default(0);
            $table->unsignedInteger('price');
            $table->integer('qty_sold')->default(0);
            $table->integer('is_seri')->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->integer('benefit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_in_details');
    }
};
