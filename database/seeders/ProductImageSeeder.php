<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            DB::table('product_images')->insert([
                'product_id' => fake()->randomElement($productIds),
                'image_url' => fake()->imageUrl(640, 480, 'tech'),
                'is_primary' => fake()->boolean(20),
            ]);
        }
    }
}
