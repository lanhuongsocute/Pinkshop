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
        Schema::create('sup_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('supplier_id') ;
            $table->unsignedBigInteger('doc_id')->default(0) ;
            $table->enum('doc_type',['wi','wo','fi','fo','si','so','mi','mo','wor','wir']);
            $table->integer('operation') ;
            $table->BigInteger('amount') ;
            $table->BigInteger('total') ;
            $table->string('content')->nullable() ;
            $table->integer('is_delete')->default(0) ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sup_transactions');
    }
};
