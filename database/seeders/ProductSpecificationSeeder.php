<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id')->all();
        $specNames = ['CPU', 'RAM', 'Storage', 'GPU', 'Display', 'Battery', 'Weight', 'Ports', 'OS', 'Warranty'];

        foreach ($specNames as $specName) {
            DB::table('product_specifications')->insert([
                'product_id' => fake()->randomElement($productIds),
                'spec_name' => $specName,
                'spec_value' => fake()->bothify('##??').' '.fake()->word(),
            ]);
        }
    }
}
