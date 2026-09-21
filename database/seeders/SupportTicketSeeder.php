<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $productIds = DB::table('products')->pluck('id')->all();
        $staffIds = DB::table('users')->whereIn('role', ['staff', 'admin'])->pluck('id')->all();
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];
        $types = ['general', 'warranty', 'technical'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        for ($i = 0; $i < 10; $i++) {
            $status = fake()->randomElement($statuses);

            DB::table('support_tickets')->insert([
                'user_id' => fake()->randomElement($userIds),
                'product_id' => fake()->boolean(70) ? fake()->randomElement($productIds) : null,
                'assigned_to' => count($staffIds) && fake()->boolean(60) ? fake()->randomElement($staffIds) : null,
                'subject' => fake()->sentence(6),
                'message' => fake()->paragraph(3),
                'status' => $status,
                'type' => fake()->randomElement($types),
                'priority' => fake()->randomElement($priorities),
                'created_at' => now()->subDays(fake()->numberBetween(0, 60)),
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(fake()->numberBetween(0, 30)) : null,
            ]);
        }
    }
}
