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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('title');
            
            $table->string('slug')->unique();
            $table->mediumText('summary')->nullable();
            $table->longText('description')->nullable();
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('sold')->default(0);
            $table->integer('price_in')->default(0);
            $table->integer('price_avg')->default(0);
            $table->integer('price_out')->default(0);
            $table->integer('price')->default(0);
            $table->integer('hit')->default(0);
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('cat_id')->default(0);
            $table->unsignedBigInteger('parent_cat_id')->nullable();
            $table->mediumText('photo');
            $table->string('size')->nullable();
            $table->double('weight')->nullable();
            $table->double('expired')->nullable();
            $table->double('is_sold')->default(1);
            $table->enum('type',['normal','digital','service'])->default('normal');
            $table->enum('status',['active','inactive'])->default('active');
            $table->boolean('feature')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
