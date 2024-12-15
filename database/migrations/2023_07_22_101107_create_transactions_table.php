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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id') ;
            $table->enum('doctype',['wi','wo','ci','co','si','so']);
            $table->integer('docoperation') ;
            $table->BigInteger('amount') ;
            $table->unsignedBigInteger('user_id') ;
            $table->BigInteger('total') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
