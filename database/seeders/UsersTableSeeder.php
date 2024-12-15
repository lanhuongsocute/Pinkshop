<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //admin
        DB::table('users')->insert([
            [
                'full_name' => 'Shop admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '111111111',
                'status' => 'active',

            ],
            [
                'full_name' => "Shop manager",
                "username" => "manager",
                "email" => "manager@gmail.com",
                "password" => Hash::make('12345678'),
                "role" => "manager",
                "phone" => "111111119",
                'status' => 'active',

            ],




        ]);
    }
}
