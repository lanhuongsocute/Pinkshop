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
        Schema::create('b_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id') ;
            $table->integer('wh_id')->default(1);
            $table->integer('quantity') ;
            $table->integer('price')->default(0);
            $table->unique(array('product_id', 'wh_id'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b_inventories');
    }
};
