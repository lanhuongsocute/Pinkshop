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
        \DB::statement("DROP VIEW IF EXISTS `warehouse_ins_view_today`; ");
        \DB::statement(" 
        CREATE VIEW warehouse_ins_view_today AS
            SELECT hour(created_at) as v_hour, final_amount ,status
            FROM warehouse_ins 
            WHERE date(created_at) = date(CURDATE());
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("
        DROP VIEW IF EXISTS `warehouse_ins_view_today`; ");
    }
};
