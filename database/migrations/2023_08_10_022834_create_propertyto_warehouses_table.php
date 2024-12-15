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
        Schema::create('propertyto_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('product_id') ;
            $table->unsignedBigInteger('wh_id') ;
            $table->integer('quantity') ;
            $table->integer('price') ;
            $table->integer('total') ;
            $table->integer('time')->nullable() ;
            $table->unsignedBigInteger('vendor_id') ;
            $table->string('in_ids')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propertyto_warehouses');
    }
};
