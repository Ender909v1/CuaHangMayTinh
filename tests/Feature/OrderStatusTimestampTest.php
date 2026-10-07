<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderStatusTimestampTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_stamps_the_initial_pending_timestamp(): void
    {
        $order = $this->placeOrder($this->makeCustomer());

        $fresh = $order->fresh();
        $this->assertNotNull($fresh->status_updated_at);
        // The first status (pending) starts when the order was placed.
        $this->assertTrue($fresh->status_updated_at->equalTo($fresh->order_date));
    }

    public function test_admin_delivery_time_is_shown_in_my_orders(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->placeOrder($customer);

        $this->actingAs($this->makeAdmin())
            ->put("/admin/orders/{$order->id}/status", ['status' => 'delivered'])
            ->assertRedirect();

        $deliveredAt = $order->fresh()->status_updated_at;
        $this->assertNotNull($deliveredAt);

        $this->actingAs($customer)->get('/my-orders')
            ->assertOk()
            ->assertSee('DELIVERED', false)
            ->assertSee('Delivered on '.$deliveredAt->format('d/m/Y H:i'), false);
    }

    public function test_customer_cancellation_time_is_shown_in_my_orders(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->placeOrder($customer);

        $this->actingAs($customer)->delete("/my-orders/{$order->id}")->assertRedirect();

        $fresh = $order->fresh();
        $this->assertEquals('cancelled', $fresh->status);
        $this->assertNotNull($fresh->status_updated_at);

        $this->actingAs($customer)->get('/my-orders')
            ->assertOk()
            ->assertSee('Cancelled on '.$fresh->status_updated_at->format('d/m/Y H:i'), false);
    }

    public function test_payment_fix_does_not_move_the_status_timestamp(): void
    {
        $order = $this->placeOrder($this->makeCustomer());

        $order->status_updated_at = now()->subDay();
        $order->save();
        $stamp = $order->fresh()->status_updated_at;

        $this->actingAs($this->makeAdmin())->put("/admin/orders/{$order->id}/payment", [
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'transaction_id' => 'TX-123',
        ])->assertRedirect();

        // The payment rescue touches updated_at, but must not move the status stamp.
        $this->assertTrue($order->fresh()->status_updated_at->equalTo($stamp));
    }

    public function test_my_orders_falls_back_to_order_date_for_legacy_rows(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->placeOrder($customer);

        // Legacy rows saved before the column existed keep a null stamp.
        Order::where('id', $order->id)->update(['status_updated_at' => null]);

        $this->actingAs($customer)->get('/my-orders')
            ->assertOk()
            ->assertSee('Pending since '.$order->fresh()->order_date->format('d/m/Y H:i'), false);
    }

    private function placeOrder(User $customer): Order
    {
        $product = $this->makeProduct();

        $this->actingAs($customer)->post('/checkout', [
            'full_name' => 'Buyer',
            'email' => 'buyer@example.com',
            'address' => '123 Street',
            'phone' => '0900000007',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertRedirect();

        return Order::latest('id')->firstOrFail();
    }

    private function makeCustomer(): User
    {
        return User::create([
            'full_name' => 'Buyer', 'email' => 'buyer@example.com',
            'password_hash' => Hash::make('secret123'), 'role' => 'customer',
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'full_name' => 'Admin', 'email' => 'admin@example.com',
            'password_hash' => Hash::make('secret123'), 'role' => 'admin',
        ]);
    }

    private function makeProduct(): Product
    {
        $brand = Brand::create(['name' => 'B'.uniqid()]);
        $category = Category::create(['name' => 'C'.uniqid()]);

        return Product::create([
            'name' => 'P'.uniqid(), 'sku' => 'SKU-'.uniqid(),
            'price' => 100, 'discount_price' => null,
            'stock_qty' => 5, 'type' => 'laptop',
            'brand_id' => $brand->id, 'category_id' => $category->id,
            'is_active' => true,
        ]);
    }
}
