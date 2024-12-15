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
        Schema::create('warehousein_detail_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wi_id') ;
            $table->unsignedBigInteger('wh_id')->default(1) ;
            $table->string('doc_type')->default('wi') ;
            $table->unsignedBigInteger('product_id') ;
            $table->string('seri') ;
            $table->boolean('is_sold') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehousein_detail_series');
    }
};
