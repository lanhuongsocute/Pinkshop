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
        Schema::create('maintain_series', function (Blueprint $table) {
            $table->id();
            $table->integer('wm_id') ;
            $table->string('doc_type') ;
            $table->unsignedBigInteger('product_id') ;
            $table->string('seri') ;
            $table->integer('is_sold')->default(0);
            $table->unsignedBigInteger('in_id') ->nullable() ;;
            $table->unsignedBigInteger('out_id')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintain_series');
    }
};
