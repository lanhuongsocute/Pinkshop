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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('global_id')->nullable();
            $table->string('full_name');
            $table->string('username')->nullable();
            $table->string('email') ;
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('phone') ;
            $table->string('address')->nullable();
            $table->mediumtext('description')->nullable();
            $table->integer('ship_id')->nullable();
            $table->integer('ugroup_id')->nullable();
            $table->string('role')->default('customer');
            $table->BigInteger('budget')->default(0);
            $table->BigInteger('totalpoint')->default(0);
            $table->BigInteger('totalrevenue')->default(0);
            $table->BigInteger('totalinvoice')->default(0);
            $table->string('taxcode')->nullable();
            $table->string('taxname')->nullable();
            $table->string('taxaddress')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
