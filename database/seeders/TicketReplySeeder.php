<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketReplySeeder extends Seeder
{
    public function run(): void
    {
        $ticketIds = DB::table('support_tickets')->pluck('id')->all();
        $userIds = DB::table('users')->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            DB::table('ticket_replies')->insert([
                'ticket_id' => fake()->randomElement($ticketIds),
                'user_id' => fake()->randomElement($userIds),
                'message' => fake()->paragraph(2),
                'created_at' => now()->subDays(fake()->numberBetween(0, 55)),
            ]);
        }
    }
}
