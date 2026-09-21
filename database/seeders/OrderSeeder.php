<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['credit_card', 'bank_transfer', 'cod', 'e_wallet'];

        for ($i = 0; $i < 10; $i++) {
            $paid = fake()->boolean(70);

            DB::table('orders')->insert([
                'user_id' => fake()->randomElement($userIds),
                'order_date' => now()->subDays(fake()->numberBetween(0, 90)),
                'total_amount' => fake()->randomFloat(2, 200000, 50000000),
                'status' => fake()->randomElement($statuses),
                'payment_method' => fake()->randomElement($paymentMethods),
                'payment_status' => $paid ? 'paid' : 'pending',
                'transaction_id' => $paid ? fake()->uuid() : null,
                'email_sent' => $paid,
                'email_sent_at' => $paid ? now()->subDays(fake()->numberBetween(0, 89)) : null,
                'shipping_address' => fake()->address(),
            ]);
        }
    }
}
