<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->inRandomOrder()->limit(10)->pluck('id')->all();
        $productIds = DB::table('products')->inRandomOrder()->limit(10)->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            DB::table('wishlists')->insert([
                'user_id' => $userIds[$i % count($userIds)],
                'product_id' => $productIds[$i],
                'added_at' => now()->subDays(fake()->numberBetween(0, 45)),
            ]);
        }
    }
}
