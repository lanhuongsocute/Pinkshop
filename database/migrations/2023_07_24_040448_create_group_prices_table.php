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
        Schema::create('group_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('ugroup_id') ;
            $table->unsignedBigInteger('price') ;
            $table->unsignedBigInteger('product_id') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_prices');
    }
};
