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
        Schema::create('warehouseout_detail_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_id') ;
            $table->string('doc_type')->default('wo') ;
            $table->unsignedBigInteger('product_id') ;
            $table->string('seri') ;
            $table->unsignedBigInteger('in_id') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouseout_detail_series');
    }
};
