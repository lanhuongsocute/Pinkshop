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
        Schema::create('combo_details', function (Blueprint $table) {
            $table->id();
            $table->integer('combo_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->enum('status',['active','inactive'])->default('active');
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_details');
    }
};
