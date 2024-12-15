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
        Schema::create('warehousetransfers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('wh_id1') ;
            $table->unsignedBigInteger('wh_id2') ;
            $table->unsignedBigInteger('vendor_id1') ;
            $table->unsignedBigInteger('vendor_id2') ;
            $table->unsignedBigInteger('author_id') ;
            $table->unsignedBigInteger('shiptrans_id')->nullable() ;
            $table->unsignedBigInteger('delivery_id')->nullable() ;
            $table->integer('cost_extra')->default(0) ;
            $table->BigInteger('total') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehousetransfers');
    }
};
