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
        Schema::create('front_pro_mod_details', function (Blueprint $table) {
            $table->id();
            $table->integer('mod_id');
            $table->integer('pro_id');
            $table->integer('order_id');
            $table->enum('status',['active','inactive'])->default('active');

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_pro_mod_details');
    }
};
