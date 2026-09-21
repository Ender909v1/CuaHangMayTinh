<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarrantyHistorySeeder extends Seeder
{
    public function run(): void
    {
        $warrantyIds = DB::table('warranties')->pluck('id')->all();
        $performers = ['System', 'Kỹ thuật viên A', 'Kỹ thuật viên B', 'Admin'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('warranty_history')->insert([
                'warranty_id' => fake()->randomElement($warrantyIds),
                'action_date' => now()->subDays(fake()->numberBetween(0, 200)),
                'description' => fake()->sentence(8),
                'performed_by' => fake()->randomElement($performers),
            ]);
        }
    }
}
