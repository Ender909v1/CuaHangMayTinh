<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductViewSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $productIds = DB::table('products')->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            $hasUser = fake()->boolean(70);

            DB::table('product_views')->insert([
                'user_id' => $hasUser ? fake()->randomElement($userIds) : null,
                'session_id' => $hasUser ? null : fake()->uuid(),
                'product_id' => fake()->randomElement($productIds),
                'viewed_at' => now()->subDays(fake()->numberBetween(0, 30)),
            ]);
        }
    }
}
