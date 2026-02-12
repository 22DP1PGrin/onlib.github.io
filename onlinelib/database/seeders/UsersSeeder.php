<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            ['email' => 'pgriniceva@gmail.com', 'nickname' => 'SuperAdmin', 'password' => Hash::make(env('SUPERADMIN_PASSWORD')),
                'role' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
            ['email' => 'patricija.griniceva@outlook.com', 'nickname' => 'Tester1', 'password' => Hash::make(env('SUPERADMIN_PASSWORD')),
                'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['email' => 'pg00217@rvt.lv', 'nickname' => 'Tester2', 'password' => Hash::make(env('SUPERADMIN_PASSWORD')),
                'role' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
