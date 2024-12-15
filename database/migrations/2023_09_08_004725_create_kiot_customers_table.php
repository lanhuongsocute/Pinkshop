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
        Schema::create('kiot_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id') ;
            $table->unsignedBigInteger('kiot_customer_id') ;
            $table->dateTime('modifiedDate')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kiot_customers');
    }
};
