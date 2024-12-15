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
        Schema::create('d_indetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id')->default(0) ;
            $table->string('doc_type' )->default('wi');
            $table->unsignedBigInteger('wh_id')->default(0) ;
            $table->unsignedBigInteger('product_id') ;
            $table->integer('quantity');
            $table->unsignedInteger('price');
            $table->integer('qty_sold')->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_indetails');
    }
};
