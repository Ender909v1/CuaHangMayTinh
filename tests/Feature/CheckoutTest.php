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

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_renders_with_form(): void
    {
        $this->get('/checkout')->assertOk()->assertSee('Checkout', false)->assertSee('checkout-form', false);
    }

    public function test_guest_can_place_order_and_get_unique_bill_code(): void
    {
        $product = $this->makeProduct(price: 100, stock: 5);

        $first = $this->post('/checkout', [
            'full_name' => 'Guest Buyer',
            'email' => 'guest@example.com',
            'address' => '123 Street',
            'phone' => '0900000001',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $first->assertRedirect();
        $order = Order::first();
        $this->assertNotNull($order->bill_code);
        $this->assertStringStartsWith('BILL-', $order->bill_code);
        $this->assertEquals(200.00, (float) $order->total_amount);
        $this->assertEquals(3, $product->fresh()->stock_qty);
        $this->assertNull($order->user_id);

        // The success page renders the store header, whose account dropdown is Alpine-driven.
        $this->get(route('checkout.success', $order->bill_code))->assertOk()->assertSee('alpinejs', false);

        $second = $this->post('/checkout', [
            'full_name' => 'Guest Buyer',
            'email' => 'guest@example.com',
            'address' => '123 Street',
            'phone' => '0900000001',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);
        $second->assertRedirect();

        $this->assertCount(2, Order::all());
        $this->assertCount(2, Order::all()->pluck('bill_code')->unique());
    }

    public function test_checkout_uses_discount_price_and_rejects_overstock(): void
    {
        $product = $this->makeProduct(price: 100, discount: 80, stock: 1);

        $this->post('/checkout', [
            'full_name' => 'Buyer',
            'email' => 'buyer@example.com',
            'address' => '123 Street',
            'phone' => '0900000002',
            'items' => [['product_id' => $product->id, 'quantity' => 5]],
        ])->assertSessionHasErrors('items');

        $this->post('/checkout', [
            'full_name' => 'Buyer',
            'email' => 'buyer@example.com',
            'address' => '123 Street',
            'phone' => '0900000002',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertRedirect();

        $this->assertEquals(80.00, (float) Order::first()->total_amount);
    }

    public function test_admin_can_view_orders_users_and_inventory(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin)->get('/admin/orders')->assertOk()->assertSee('Orders', false);
        $this->actingAs($admin)->get('/admin/users')->assertOk()->assertSee('All accounts', false);
        $this->actingAs($admin)->get('/admin/inventory')->assertOk()->assertSee('Stock levels', false);
        $this->actingAs($admin)->get('/admin?tab=users')->assertOk()->assertSee('Users Management', false);
        // Dashboard tabs render full lists inline (no "show all" buttons anymore).
        $this->actingAs($admin)->get('/admin?tab=orders')->assertOk()->assertDontSee('All Orders', false);
        $this->actingAs($admin)->get('/admin?tab=inventory')->assertOk()->assertDontSee('Full Inventory', false);
    }

    public function test_admin_can_mark_order_delivered(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct(price: 100, stock: 5);
        $customer = User::create([
            'full_name' => 'Buyer', 'email' => 'buyer@example.com',
            'password_hash' => Hash::make('secret123'), 'role' => 'customer',
        ]);

        $this->actingAs($customer)->post('/checkout', [
            'full_name' => 'Buyer', 'email' => 'buyer@example.com',
            'address' => '123 Street', 'phone' => '0900000009',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertRedirect();

        $order = Order::first();
        $this->actingAs($admin)->put("/admin/orders/{$order->id}/status", ['status' => 'delivered'])
            ->assertRedirect();
        $this->assertEquals('delivered', $order->fresh()->status);

        // Delivered badge shows in the customer's purchase history.
        $this->actingAs($customer)->get('/my-orders')->assertOk()->assertSee('DELIVERED', false);
    }

    public function test_order_seen_tracking_clears_after_viewing_history(): void
    {
        $customer = User::create([
            'full_name' => 'Buyer', 'email' => 'alert@example.com',
            'password_hash' => Hash::make('secret123'), 'role' => 'customer',
        ]);
        $product = $this->makeProduct(price: 50, stock: 5);

        // New purchase -> unseen, so it needs attention.
        $this->actingAs($customer)->post('/checkout', [
            'full_name' => 'Buyer', 'email' => 'alert@example.com',
            'address' => '123 Street', 'phone' => '0900000010',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertRedirect();

        $this->assertEquals(1, $customer->orders()->whereNull('user_seen_at')->count());

        // The header shows the receipt link, but no "!" alert badge on it anymore.
        $home = $this->actingAs($customer)->get('/');
        $home->assertOk();
        $home->assertSee('fa-receipt', false);
        $home->assertDontSee('unchecked order update');

        // Visiting history still clears the unseen flag.
        $this->actingAs($customer)->get('/my-orders')->assertOk();
        $this->assertEquals(0, $customer->orders()->whereNull('user_seen_at')->count());

        // Admin status update re-triggers attention.
        $admin = $this->makeAdmin();
        $order = Order::first();
        $this->actingAs($admin)->put("/admin/orders/{$order->id}/status", ['status' => 'shipped']);
        $this->assertTrue($order->fresh()->needsAttention());
    }

    public function test_admin_cannot_edit_or_delete_admin_accounts(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Customer', 'email' => 'c@example.com',
            'password_hash' => Hash::make('secret123'), 'role' => 'customer',
        ]);

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'full_name' => 'Hacked', 'email' => 'hacked@example.com', 'role' => 'customer',
        ])->assertSessionHasErrors('user');

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")->assertSessionHasErrors('user');

        $this->actingAs($admin)->put("/admin/users/{$customer->id}", [
            'full_name' => 'Updated', 'email' => 'c@example.com', 'role' => 'customer',
        ])->assertRedirect();
        $this->assertEquals('Updated', $customer->fresh()->full_name);

        $this->actingAs($admin)->delete("/admin/users/{$customer->id}")->assertRedirect();
        $this->assertNull(User::find($customer->id));
    }

    public function test_out_of_stock_product_shows_details_only(): void
    {
        $product = $this->makeProduct(price: 50, stock: 0);

        $this->get('/')->assertOk()->assertSee('Out of stock', false)->assertSee('View Details', false);
        $this->get('/product/'.$product->id)->assertOk()->assertSee('Out of Stock', false)->assertDontSee('data-add-to-cart', false);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'full_name' => 'Admin', 'email' => 'Admin@pc.lap',
            'password_hash' => Hash::make('Admin_123'), 'role' => 'admin',
        ]);
    }

    private function makeProduct(float $price, int $stock, ?float $discount = null): Product
    {
        $brand = Brand::create(['name' => 'B'.uniqid()]);
        $category = Category::create(['name' => 'C'.uniqid()]);

        return Product::create([
            'name' => 'P'.uniqid(), 'sku' => 'SKU-'.uniqid(),
            'price' => $price, 'discount_price' => $discount,
            'stock_qty' => $stock, 'type' => 'laptop',
            'brand_id' => $brand->id, 'category_id' => $category->id,
            'is_active' => true,
        ]);
    }
}
