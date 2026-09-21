<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parentIds = [];

        for ($i = 0; $i < 5; $i++) {
            $parentIds[] = DB::table('categories')->insertGetId([
                'name' => fake()->unique()->word(),
                'parent_id' => null,
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            DB::table('categories')->insert([
                'name' => fake()->unique()->word(),
                'parent_id' => fake()->randomElement($parentIds),
            ]);
        }
    }
}
