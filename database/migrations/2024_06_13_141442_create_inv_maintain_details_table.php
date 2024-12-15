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
        Schema::create('inv_maintain_details', function (Blueprint $table) {
            $table->id();
            $table->integer('doc_id') ;
            $table->string('doc_type') ;
            $table->integer('is_delete') ;
            $table->unsignedBigInteger('product_id') ;
            $table->integer('quantity') ;
            $table->integer('price') ;
            $table->integer('qty_sold') ;
            $table->integer('operation') ;
            $table->integer('balance') ;
            $table->string('in_ids') ;
            $table->string('is_seri') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_maintain_details');
    }
};
