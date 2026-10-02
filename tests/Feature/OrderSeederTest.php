<?php

namespace Tests\Feature;

use Database\Seeders\OrderSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_orders_creates_unique_bill_codes(): void
    {
        $this->seed(UserSeeder::class);

        $this->seed(OrderSeeder::class);

        $orders = DB::table('orders');

        $this->assertDatabaseCount('orders', 10);
        $this->assertSame(0, (clone $orders)->whereNull('bill_code')->count());
        $this->assertSame(10, (clone $orders)->distinct('bill_code')->count('bill_code'));
    }
}
