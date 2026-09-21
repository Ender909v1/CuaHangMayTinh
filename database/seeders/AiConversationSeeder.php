<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiConversationSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            $hasUser = fake()->boolean(60);

            DB::table('ai_conversations')->insert([
                'user_id' => $hasUser ? fake()->randomElement($userIds) : null,
                'session_id' => $hasUser ? null : fake()->uuid(),
                'message' => fake()->sentence(10),
                'response' => fake()->boolean(90) ? fake()->paragraph(2) : null,
                'created_at' => now()->subDays(fake()->numberBetween(0, 20)),
            ]);
        }
    }
}
