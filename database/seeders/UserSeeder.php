<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['customer', 'customer', 'customer', 'customer', 'customer', 'customer', 'customer', 'customer', 'staff', 'admin'];

        foreach ($roles as $role) {
            DB::table('users')->insert([
                'full_name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password_hash' => Hash::make('password'),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'role' => $role,
                'is_active' => true,
                'created_at' => now(),
            ]);
        };

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
    }
}
