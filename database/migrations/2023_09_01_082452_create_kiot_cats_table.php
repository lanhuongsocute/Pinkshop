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
        Schema::create('kiot_cats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoryId') ;
            $table->unsignedBigInteger('kiotId') ;
            $table->unsignedBigInteger('parentId')->nullable() ;
            $table->unsignedBigInteger('parentKiotId')->nullable() ;
            $table->dateTime('modifiedDate')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kiot_cats');
    }
};
