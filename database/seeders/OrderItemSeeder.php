<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orderIds = DB::table('orders')->pluck('id')->all();
        $products = DB::table('products')->select('id', 'price')->get();

        for ($i = 0; $i < 10; $i++) {
            $product = $products->random();

            DB::table('order_items')->insert([
                'order_id' => fake()->randomElement($orderIds),
                'product_id' => $product->id,
                'quantity' => fake()->numberBetween(1, 4),
                'unit_price' => $product->price,
            ]);
        }
    }
}
