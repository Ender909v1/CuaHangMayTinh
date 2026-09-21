<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarrantySeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $productIds = DB::table('products')->pluck('id')->all();
        $orderItemIds = DB::table('order_items')->pluck('id')->all();
        $statuses = ['active', 'expired', 'claimed', 'void'];

        for ($i = 0; $i < 10; $i++) {
            $start = now()->subDays(fake()->numberBetween(0, 300));

            DB::table('warranties')->insert([
                'user_id' => fake()->randomElement($userIds),
                'product_id' => fake()->randomElement($productIds),
                'order_item_id' => fake()->boolean(80) ? fake()->randomElement($orderItemIds) : null,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addMonths(24)->toDateString(),
                'status' => fake()->randomElement($statuses),
            ]);
        }
    }
}
