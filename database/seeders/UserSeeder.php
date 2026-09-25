<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'Admin@pc.lap'],
            [
                'full_name' => 'Admin User',
                'password_hash' => Hash::make('Admin_123'),
                'phone' => '0900000000',
                'role' => 'admin',
                'created_at' => now(),
            ]
        );

        $customers = 8;
        for ($i = 0; $i < $customers; $i++) {
            DB::table('users')->updateOrInsert(
                ['email' => 'customer' . ($i + 1) . '@example.com'],
                [
                    'full_name' => fake()->name(),
                    'password_hash' => Hash::make('password'),
                    'phone' => fake()->phoneNumber(),
                    'role' => 'customer',
                    'created_at' => now(),
                ]
            );
        }
    }
}
