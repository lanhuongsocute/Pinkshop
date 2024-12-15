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
        Schema::create('kiot_warehouse_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehousein_id') ;
            $table->unsignedBigInteger('kiot_warehousein_id') ;
            $table->dateTime('modifiedDate')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kiot_warehouse_ins');
    }
};
