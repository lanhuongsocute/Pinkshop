<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('u_groups')->insert([
            [
                'id' => 0,
                'title' => "khách lẻ",
                'status' => 'active',

            ],


        ]);
    }
}
