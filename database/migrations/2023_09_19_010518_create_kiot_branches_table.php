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
        Schema::create('kiot_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id') ;
            $table->unsignedBigInteger('kiot_branch_id') ;
            $table->dateTime('modifiedDate')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kiot_branches');
    }
};
