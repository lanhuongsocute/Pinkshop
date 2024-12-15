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
        Schema::create('maintain_backs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id') ;
            $table->unsignedBigInteger('vendor_id') ;
            $table->integer('shipcost')->nullalbe() ;
            $table->integer('cost_extra')->nullalbe() ;
            $table->integer('final_amount')->default(0) ;
            $table->integer('paid_amount')->default(0) ;
            $table->unsignedBigInteger('shiptrans_id')->nullable() ;
            $table->unsignedBigInteger('suptrans_id')->nullable() ;
            $table->string('paidtrans_ids')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintain_backs');
    }
};
