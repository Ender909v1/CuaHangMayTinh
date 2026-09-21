<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->inRandomOrder()->limit(10)->pluck('id')->all();
        $productIds = DB::table('products')->inRandomOrder()->limit(10)->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            DB::table('cart_items')->insert([
                'user_id' => $userIds[$i % count($userIds)],
                'product_id' => $productIds[$i],
                'quantity' => fake()->numberBetween(1, 5),
                'added_at' => now()->subDays(fake()->numberBetween(0, 10)),
            ]);
        }
    }
}
