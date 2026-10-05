<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOrderFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_page_filters_by_search_status_and_date_range(): void
    {
        $admin = $this->makeAdmin();
        $alice = $this->makeCustomer('Alice Nguyen', 'alice@example.com');
        $bob = $this->makeCustomer('Bob Tran', 'bob@example.com');

        $aliceOrder = $this->makeOrder($alice, 'BILL-ALICE-001', 'pending', '2026-09-01 10:00:00');
        $bobOrder = $this->makeOrder($bob, 'BILL-BOB-002', 'delivered', '2026-10-03 10:00:00');

        // Search matches bill code.
        $this->actingAs($admin)->get('/admin/orders?search=ALICE-001')
            ->assertOk()
            ->assertSee('BILL-ALICE-001', false)
            ->assertDontSee('BILL-BOB-002', false);

        // Search matches customer name.
        $this->actingAs($admin)->get('/admin/orders?search=Bob+Tran')
            ->assertOk()
            ->assertSee('BILL-BOB-002', false)
            ->assertDontSee('BILL-ALICE-001', false);

        // Status droplist filters.
        $this->actingAs($admin)->get('/admin/orders?status=delivered')
            ->assertOk()
            ->assertSee('BILL-BOB-002', false)
            ->assertDontSee('BILL-ALICE-001', false);

        // Order-date range filters on order_date.
        $this->actingAs($admin)->get('/admin/orders?date_from=2026-10-01&date_to=2026-10-05')
            ->assertOk()
            ->assertSee('BILL-BOB-002', false)
            ->assertDontSee('BILL-ALICE-001', false);

        // Filters combine (search + status + date range).
        $this->actingAs($admin)
            ->get('/admin/orders?search=BILL&status=pending&date_from=2026-09-01&date_to=2026-09-30')
            ->assertOk()
            ->assertSee('BILL-ALICE-001', false)
            ->assertDontSee('BILL-BOB-002', false);

        $this->assertTrue($aliceOrder->is($aliceOrder->fresh()));
        $this->assertTrue($bobOrder->is($bobOrder->fresh()));
    }

    public function test_dashboard_orders_tab_supports_same_filters(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer('Carol Pham', 'carol@example.com');
        $this->makeOrder($customer, 'BILL-CAROL-003', 'shipped', '2026-10-04 10:00:00');
        $this->makeOrder($customer, 'BILL-CAROL-004', 'pending', '2026-08-01 10:00:00');

        $this->actingAs($admin)->get('/admin?tab=orders&search=CAROL-003&status=shipped&date_from=2026-10-01&date_to=2026-10-05')
            ->assertOk()
            ->assertSee('Orders Management', false)
            ->assertSee('BILL-CAROL-003', false)
            ->assertDontSee('BILL-CAROL-004', false);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'full_name' => 'Admin', 'email' => 'admin-orders@example.com',
            'password_hash' => Hash::make('Admin_123'), 'role' => 'admin',
        ]);
    }

    private function makeCustomer(string $name, string $email): User
    {
        return User::create([
            'full_name' => $name, 'email' => $email,
            'password_hash' => Hash::make('secret123'), 'role' => 'customer',
        ]);
    }

    private function makeOrder(User $customer, string $billCode, string $status, string $orderDate): Order
    {
        return Order::create([
            'bill_code' => $billCode,
            'user_id' => $customer->id,
            'order_date' => $orderDate,
            'total_amount' => 100,
            'status' => $status,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 Street',
        ]);
    }
}
