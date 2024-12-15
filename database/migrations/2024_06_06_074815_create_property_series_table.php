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
        Schema::create('property_series', function (Blueprint $table) {
            $table->id();
            $table->integer('wp_id') ;
            $table->string('doc_type')->default('wp') ;
            $table->unsignedBigInteger('product_id') ;
            $table->string('seri') ;
            $table->integer('is_sold')->default(0);
            $table->unsignedBigInteger('in_id') ;
            $table->unsignedBigInteger('out_id') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_series');
    }
};
