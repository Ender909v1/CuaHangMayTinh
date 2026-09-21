<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brandIds = DB::table('brands')->pluck('id')->all();
        $categoryIds = DB::table('categories')->pluck('id')->all();
        $types = ['physical', 'physical', 'physical', 'digital', 'service'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('products')->insert([
                'name' => ucfirst(fake()->words(3, true)),
                'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
                'description' => fake()->paragraph(),
                'price' => fake()->randomFloat(2, 100000, 30000000),
                'discount_price' => fake()->optional(0.4)->randomFloat(2, 90000, 25000000),
                'stock_qty' => fake()->numberBetween(0, 200),
                'type' => fake()->randomElement($types),
                'brand_id' => fake()->randomElement($brandIds),
                'category_id' => fake()->randomElement($categoryIds),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
