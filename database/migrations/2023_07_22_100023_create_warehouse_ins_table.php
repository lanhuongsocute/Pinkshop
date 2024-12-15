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
        Schema::create('warehouse_ins', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('version')->default(1) ;
            $table->unsignedBigInteger('wh_id') ;
            $table->unsignedBigInteger('supplier_id') ;
            $table->unsignedBigInteger('vendor_id') ;
            $table->integer('final_amount') ;
            $table->integer('discount_amount')->default(0) ;
            $table->integer('paid_amount')->default(0) ;
            $table->boolean('is_paid')->default(0) ;
            $table->unsignedBigInteger('suptrans_id')->nullable() ;
            $table->string('paidtrans_ids')->nullable() ;
            $table->unsignedBigInteger('shiptrans_id')->nullable() ;
            $table->integer('cost_extra')->default(0) ;
            $table->string('status')->default('active');
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_ins');
    }
};
