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
        Schema::create('systrans', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('operation');
            $table->integer('total');
            $table->integer('scount');
            $table->BigInteger('prebalance');
            $table->integer('precount');
            $table->BigInteger('doc_id');
            $table->string('doc_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systrans');
    }
};
