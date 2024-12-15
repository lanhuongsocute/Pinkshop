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
        Schema::create('maintain_sents', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('supplier_id') ;
            $table->unsignedBigInteger('vendor_id') ;
            $table->integer('shipcost')->nullalbe() ;
            $table->integer('cost_extra')->nullalbe() ;
            $table->unsignedBigInteger('shiptrans_id')->nullable() ;
            $table->unsignedBigInteger('delivery_id')->nullable() ;
            $table->enum('status',[ 'sent','back' ])->default('sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintain_sents');
    }
};
