<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $productIds = DB::table('products')->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            DB::table('reviews')->insert([
                'user_id' => fake()->randomElement($userIds),
                'product_id' => fake()->randomElement($productIds),
                'rating' => fake()->numberBetween(1, 5),
                'comment' => fake()->boolean(85) ? fake()->paragraph(2) : null,
                'created_at' => now()->subDays(fake()->numberBetween(0, 60)),
            ]);
        }
    }
}
