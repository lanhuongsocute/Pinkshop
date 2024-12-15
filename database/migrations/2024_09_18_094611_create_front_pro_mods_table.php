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
        Schema::create('front_pro_mods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('order_id')->default(0);;
            $table->integer('mod_type')->default(0);
            $table->integer('op_type')->default(0);;
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_pro_mods');
    }
};
