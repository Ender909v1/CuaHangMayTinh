<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('brands')->insert([
                'name' => fake()->unique()->company(),
                'description' => fake()->sentence(12),
                'logo_url' => fake()->imageUrl(200, 200, 'business'),
            ]);
        }
    }
}
