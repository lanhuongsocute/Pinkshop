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
        Schema::create('systemaccountyears', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('year') ;
            $table->integer('month');
            $table->BigInteger('total')->default(0);
            $table->integer('scount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systemaccountyears');
    }
};
