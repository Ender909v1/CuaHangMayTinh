<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $this->makeAdmin();

        $response = $this->post('/login', [
            'email' => 'Admin@pc.lap',
            'password' => 'Admin_123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);

        $dashboard = $this->get('/admin');
        $dashboard->assertOk();
        $dashboard->assertSee('Admin Dashboard');
    }

    public function test_dashboard_lists_recent_product_history(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'action' => 'updated',
            'details' => 'Admin updated product: Test Laptop',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('Test Laptop');
        $response->assertSee('Admin updated product: Test Laptop');
    }

    public function test_dashboard_shows_product_additions_and_stock_health_statistics(): void
    {
        $admin = $this->makeAdmin();
        $availableProduct = $this->makeProduct();
        $availableProduct->update(['stock_qty' => 8]);

        $lowStockProduct = $this->makeProduct();
        $lowStockProduct->forceFill(['created_at' => now()->startOfMonth()->subMonth()])->save();

        $outOfStockProduct = $this->makeProduct();
        $outOfStockProduct->update(['stock_qty' => 0]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSeeInOrder([
            'Product statistics',
            'Product additions',
            'Stock health',
            'Recent admin history',
        ]);
        $response->assertSee('In stock: 1', false);
        $response->assertSee('Low stock: 1', false);
        $response->assertSee('Out of stock: 1', false);
        $response->assertSee(strtoupper(now()->format('M')).': 2 products added', false);
        $response->assertSee(strtoupper(now()->startOfMonth()->subMonth()->format('M')).': 1 products added', false);
    }

    public function test_admin_products_index_renders(): void
    {
        $admin = $this->makeAdmin();
        $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin/products');

        $response->assertOk();
        $response->assertSee('Test Laptop');
    }

    public function test_admin_product_lists_show_twenty_products_per_page(): void
    {
        $admin = $this->makeAdmin();
        $productIds = [];

        for ($index = 1; $index <= 21; $index++) {
            $product = $this->makeProduct();
            $product->update(['name' => 'Paginated Product '.$index]);
            $productIds[] = $product->id;
        }

        $productsPage = $this->actingAs($admin)->get('/admin/products');
        $productsPage->assertOk();
        $productsPage->assertSee('page=2', false);
        $productsPage->assertViewHas('products', fn ($products): bool => $products->count() === 20
            && $products->first()->id === $productIds[20]
            && $products->last()->id === $productIds[1]);

        $productsPageTwo = $this->actingAs($admin)->get('/admin/products?page=2');
        $productsPageTwo->assertOk();
        $productsPageTwo->assertViewHas('products', fn ($products): bool => $products->count() === 1
            && $products->first()->id === $productIds[0]);

        $dashboardProductsPage = $this->actingAs($admin)->get('/admin?tab=products');
        $dashboardProductsPage->assertOk();
        $dashboardProductsPage->assertSee('tab=products', false);
        $dashboardProductsPage->assertSee('page=2', false);
        $dashboardProductsPage->assertViewHas('products', fn ($products): bool => $products->count() === 20
            && $products->first()->id === $productIds[20]
            && $products->last()->id === $productIds[1]);

        $dashboardProductsPageTwo = $this->actingAs($admin)->get('/admin?tab=products&page=2');
        $dashboardProductsPageTwo->assertOk();
        $dashboardProductsPageTwo->assertViewHas('products', fn ($products): bool => $products->count() === 1
            && $products->first()->id === $productIds[0]);
    }

    public function test_admin_product_detail_renders_with_images_and_specifications(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => 'images/test.jpg',
            'is_primary' => true,
        ]);
        ProductSpecification::create([
            'product_id' => $product->id,
            'spec_name' => 'CPU',
            'spec_value' => 'Intel i7',
        ]);

        $response = $this->actingAs($admin)->get('/admin/products/'.$product->id);

        $response->assertOk();
        $response->assertSee('Test Laptop');
        $response->assertSee('CPU');
        $response->assertSee('Intel i7');
    }

    public function test_admin_history_page_renders(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'action' => 'created',
            'details' => 'Admin created product: Test Laptop',
        ]);

        $response = $this->actingAs($admin)->get('/admin/history');

        $response->assertOk();
        $response->assertSee('Admin created product: Test Laptop');
    }

    public function test_admin_entity_changes_appear_in_history_without_exposing_passwords(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'History Customer',
            'email' => 'history-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();
        $category = Category::create(['name' => 'Old Category']);
        $order = Order::create([
            'bill_code' => 'BILL-HISTORY-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 100,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 History Street',
        ]);
        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Good product.',
        ]);

        $this->actingAs($admin)->put('/admin/products/'.$product->id, [
            'name' => 'Renamed Laptop',
            'sku' => $product->sku,
            'price' => 1099.99,
            'discount_price' => null,
            'type' => 'laptop',
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'is_active' => 1,
        ])->assertRedirect('/admin/products');
        $this->actingAs($admin)->put('/admin/categories/'.$category->id, [
            'name' => 'Updated Category',
            'parent_id' => null,
        ])->assertRedirect('/admin/categories');
        $this->actingAs($admin)->put('/admin/orders/'.$order->id.'/status', [
            'status' => 'processing',
        ])->assertRedirect();
        $this->actingAs($admin)->put('/admin/orders/'.$order->id.'/payment', [
            'payment_method' => 'e_wallet',
            'payment_status' => 'paid',
            'transaction_id' => 'TXN-HISTORY-001',
        ])->assertRedirect();
        $this->actingAs($admin)->put('/admin/users/'.$customer->id, [
            'full_name' => 'Updated History Customer',
            'email' => 'history-customer@example.com',
            'phone' => '0900000001',
            'address' => '456 Updated Street',
            'role' => 'staff',
            'is_active' => 1,
            'password' => 'Changed_Secret_123',
            'password_confirmation' => 'Changed_Secret_123',
        ])->assertRedirect('/admin/users');
        $this->actingAs($admin)->put('/admin/reviews/'.$review->id.'/response', [
            'admin_response' => 'Thank you for your feedback.',
        ])->assertRedirect();

        $historyPage = $this->actingAs($admin)->get('/admin/history');

        $historyPage->assertOk();
        $historyPage->assertSee('Admin History');
        $historyPage->assertSee('Admin updated product #'.$product->id.' (Renamed Laptop)');
        $historyPage->assertSee('name: Test Laptop → Renamed Laptop');
        $historyPage->assertSee('Admin updated category #'.$category->id.' (Updated Category)');
        $historyPage->assertSee('name: Old Category → Updated Category');
        $historyPage->assertSee('Admin status_updated order #'.$order->id.' (BILL-HISTORY-001)');
        $historyPage->assertSee('status: pending → processing');
        $historyPage->assertSee('Admin payment_updated order #'.$order->id.' (BILL-HISTORY-001)');
        $historyPage->assertSee('transaction_id: empty → TXN-HISTORY-001');
        $historyPage->assertSee('Admin updated user #'.$customer->id.' (Updated History Customer)');
        $historyPage->assertSee('password: unchanged → changed');
        $historyPage->assertSee('Admin responded_to review #'.$review->id.' (Renamed Laptop)');
        $historyPage->assertSee('Thank you for your feedback.');
        $historyPage->assertSee('By Admin User');
        $historyPage->assertDontSee('Changed_Secret_123');
    }

    public function test_admin_detail_and_edit_pages_keep_the_full_admin_navigation(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Navigation Customer',
            'email' => 'navigation-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();
        $order = Order::create([
            'bill_code' => 'BILL-NAV-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 100,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 Navigation Street',
        ]);

        foreach ([
            '/admin',
            '/admin/products/'.$product->id,
            '/admin/products/'.$product->id.'/edit',
            '/admin/orders',
            '/admin/users/'.$customer->id.'/edit',
        ] as $path) {
            $page = $this->actingAs($admin)->get($path);
            $page->assertOk();
            $page->assertSee('Home');
            $page->assertSee(route('cuahangmaytinh'), false);
            $page->assertSee(route('admin.products.index'), false);
            $page->assertSee(route('admin.orders.index'), false);
            $page->assertSee(route('admin.users.index'), false);
            $page->assertSee(route('admin.history'), false);
        }
    }

    public function test_admin_order_detail_renders_all_action_routes(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Order Detail Customer',
            'email' => 'order-detail-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();
        $order = Order::create([
            'bill_code' => 'BILL-DETAIL-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 100,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 Detail Street',
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 100,
        ]);

        $response = $this->actingAs($admin)->get('/admin/orders/'.$order->id);

        $response->assertOk();
        $response->assertSee(route('admin.orders.cancel', $order), false);
        $response->assertSee(route('admin.order-items.destroy', [$order, $item]), false);
    }

    public function test_admin_can_cancel_pending_order_and_restock_all_items(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Order Cancel Customer',
            'email' => 'order-cancel-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();
        $order = Order::create([
            'bill_code' => 'BILL-CANCEL-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 200,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 Cancel Street',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 100,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.cancel', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame('failed', $order->fresh()->payment_status);
        $this->assertSame(5, $product->fresh()->stock_qty);
        $this->assertDatabaseHas('product_histories', [
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'action' => 'order_cancelled',
            'old_stock_qty' => 3,
            'new_stock_qty' => 5,
        ]);
        $this->assertDatabaseHas('product_histories', [
            'user_id' => $admin->id,
            'action' => 'cancelled',
        ]);
    }

    public function test_admin_can_remove_order_item_restock_it_and_cancel_when_last_item_is_removed(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Order Item Customer',
            'email' => 'order-item-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $firstProduct = $this->makeProduct();
        $secondProduct = $this->makeProduct();
        $order = Order::create([
            'bill_code' => 'BILL-ITEM-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 45,
            'status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'shipping_address' => '123 Item Street',
        ]);
        $firstItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $firstProduct->id,
            'quantity' => 2,
            'unit_price' => 10,
        ]);
        $secondItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $secondProduct->id,
            'quantity' => 1,
            'unit_price' => 25,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.order-items.destroy', [$order, $firstItem]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('order_items', ['id' => $firstItem->id]);
        $this->assertSame(5, $firstProduct->fresh()->stock_qty);
        $this->assertSame('25.00', $order->fresh()->total_amount);
        $this->assertSame('pending', $order->fresh()->status);

        $this->actingAs($admin)
            ->delete(route('admin.order-items.destroy', [$order, $secondItem]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('order_items', ['id' => $secondItem->id]);
        $this->assertSame(4, $secondProduct->fresh()->stock_qty);
        $this->assertSame('0.00', $order->fresh()->total_amount);
        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame('failed', $order->fresh()->payment_status);
        $this->assertDatabaseHas('product_histories', [
            'product_id' => $firstProduct->id,
            'user_id' => $admin->id,
            'action' => 'order_item_removed',
        ]);
    }

    public function test_admin_cannot_cancel_a_paid_order_or_restock_it(): void
    {
        $admin = $this->makeAdmin();
        $customer = User::create([
            'full_name' => 'Paid Order Customer',
            'email' => 'paid-order-customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();
        $order = Order::create([
            'bill_code' => 'BILL-PAID-001',
            'user_id' => $customer->id,
            'order_date' => '2026-10-01 10:00:00',
            'total_amount' => 100,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'shipping_address' => '123 Paid Street',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 100,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.cancel', $order));

        $response->assertRedirect();
        $response->assertSessionHasErrors('order');
        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame(3, $product->fresh()->stock_qty);
        $this->assertDatabaseMissing('product_histories', ['action' => 'order_cancelled']);
    }

    public function test_admin_can_create_product_from_form(): void
    {
        $admin = $this->makeAdmin();
        $brand = Brand::create(['name' => 'ACME']);
        $category = Category::create(['name' => 'Gaming']);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Gaming PC',
            'sku' => 'GAME-001',
            'description' => 'A custom gaming desktop.',
            'price' => 1499.99,
            'discount_price' => 1299.99,
            'stock_qty' => 10,
            'type' => 'desktop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'Gaming PC', 'sku' => 'GAME-001']);
    }

    public function test_admin_can_upload_product_image_when_creating_product(): void
    {
        $admin = $this->makeAdmin();
        $brand = Brand::create(['name' => 'ACME']);
        $category = Category::create(['name' => 'Gaming']);

        Storage::fake('public');
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAF'.
            'c0xTAAAAAXNSR0IArs4c6QAAAA1JREFUGFdjYAAAAAIAAeIhvAAAAABJRU5ErkJggg==');
        $tempPath = tempnam(sys_get_temp_dir(), 'product-image');
        file_put_contents($tempPath, $png);
        $file = new UploadedFile($tempPath, 'test-product-image.png', 'image/png', null, true);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Upload Test PC',
            'sku' => 'UPLOAD-001',
            'description' => 'Product with uploaded image.',
            'price' => 1999.99,
            'stock_qty' => 7,
            'type' => 'desktop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'image' => $file,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'Upload Test PC', 'sku' => 'UPLOAD-001']);
        $this->assertDatabaseHas('product_images', ['product_id' => Product::where('sku', 'UPLOAD-001')->value('id')]);

        $uploadedImagePath = str_replace('storage/', '', ProductImage::query()->first()->image_url);
        Storage::disk('public')->assertExists($uploadedImagePath);
    }

    public function test_admin_product_edit_page_renders_form_fields(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin/products/'.$product->id.'/edit');

        $response->assertOk();
        $response->assertSee('Edit Product');
        $response->assertSee('Brand');
        $response->assertSee('Category');
    }

    public function test_product_edit_form_shows_stock_as_read_only(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin/products/'.$product->id.'/edit');

        $response->assertOk();
        $response->assertSee('Change in Inventory');
        $response->assertDontSee('name="stock_qty"', false);
    }

    public function test_product_edit_form_cannot_change_stock(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->put('/admin/products/'.$product->id, [
            'name' => 'Renamed Laptop',
            'sku' => $product->sku,
            'price' => 1099.99,
            'discount_price' => null,
            'stock_qty' => 50,
            'type' => 'laptop',
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertEquals('Renamed Laptop', $product->fresh()->name);
        $this->assertEquals(3, $product->fresh()->stock_qty);
        $this->assertDatabaseMissing('product_histories', ['action' => 'stock_updated']);
    }

    public function test_dashboard_uses_selected_tab_from_query_string(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get('/admin?tab=products');

        $response->assertOk();
        $response->assertSee('URLSearchParams(window.location.search)');
    }

    public function test_admin_categories_tab_has_management_context(): void
    {
        $admin = $this->makeAdmin();
        Category::create(['name' => 'Accessories']);

        $response = $this->actingAs($admin)->get('/admin?tab=categories');

        $response->assertOk();
        $response->assertSee('Categories Management');
        $response->assertSee('Accessories');
        $response->assertSee('Add Category');
    }

    public function test_storefront_product_detail_page_uses_real_product_data(): void
    {
        $brand = Brand::create(['name' => 'ASUS']);
        $category = Category::create(['name' => 'Laptop']);
        $product = Product::create([
            'name' => 'ASUS ZenBook 14',
            'sku' => 'LAP-ZEN-001',
            'description' => 'Lightweight laptop for work and study.',
            'price' => 24990000,
            'discount_price' => 21990000,
            'stock_qty' => 8,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get('/product/'.$product->id);

        $response->assertOk();
        $response->assertSee('ASUS ZenBook 14');
        $response->assertSee('LAP-ZEN-001');
    }

    public function test_non_admin_customer_cannot_access_admin_area(): void
    {
        $customer = User::create([
            'full_name' => 'Minh Customer',
            'email' => 'minh@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);

        $this->actingAs($customer)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_change_stock_with_a_note_and_the_change_is_recorded(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->put("/admin/inventory/{$product->id}/stock", [
            'stock_qty' => 12,
            'note' => 'Received 9 extra units from the supplier.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals(12, $product->fresh()->stock_qty);

        $this->assertDatabaseHas('product_histories', [
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'action' => 'stock_updated',
            'old_stock_qty' => 3,
            'new_stock_qty' => 12,
            'note' => 'Received 9 extra units from the supplier.',
        ]);

        // The inventory tab keeps the Edit/History buttons and shows the note in the history panel.
        $inventoryTab = $this->actingAs($admin)->get('/admin?tab=inventory');

        $inventoryTab->assertOk();
        $inventoryTab->assertSee('/admin/inventory/'.$product->id.'/stock');
        $inventoryTab->assertSee('History');
        $inventoryTab->assertSee('Stock: 3 → 12');
        $inventoryTab->assertSee('Received 9 extra units from the supplier.');
    }

    public function test_stock_change_requires_a_note(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->put("/admin/inventory/{$product->id}/stock", [
            'stock_qty' => 9,
        ]);

        $response->assertSessionHasErrors('note');
        $this->assertEquals(3, $product->fresh()->stock_qty);
        $this->assertDatabaseCount('product_histories', 0);
    }

    public function test_stock_change_rejects_an_invalid_quantity(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->put("/admin/inventory/{$product->id}/stock", [
            'stock_qty' => -2,
            'note' => 'Typo on the supplier delivery form.',
        ]);

        $response->assertSessionHasErrors('stock_qty');
        $this->assertEquals(3, $product->fresh()->stock_qty);
        $this->assertDatabaseCount('product_histories', 0);
    }

    public function test_inventory_page_shows_edit_and_history_buttons_for_each_product(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin/inventory');

        $response->assertOk();
        $response->assertSee('History');
        $response->assertSee('Edit product');
        $response->assertSee('/admin/inventory/'.$product->id.'/stock');
    }

    public function test_customer_cannot_change_stock(): void
    {
        $customer = User::create([
            'full_name' => 'Minh Customer',
            'email' => 'minh@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
        $product = $this->makeProduct();

        $this->actingAs($customer)->put("/admin/inventory/{$product->id}/stock", [
            'stock_qty' => 99,
            'note' => 'Trying to sneak stock in.',
        ])->assertForbidden();

        $this->assertEquals(3, $product->fresh()->stock_qty);
        $this->assertDatabaseCount('product_histories', 0);
    }

    public function test_dashboard_does_not_show_a_brands_tab(): void
    {
        $response = $this->actingAs($this->makeAdmin())->get('/admin');

        $response->assertOk();
        $response->assertDontSee("activeTab = 'brands'", false);
        $response->assertDontSee('Brands Management', false);
    }

    public function test_products_tab_shows_a_delete_button_for_each_product(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin?tab=products');

        $response->assertOk();
        $response->assertSee(route('admin.products.destroy', $product), false);
        $response->assertSee('Delete this product?', false);
    }

    public function test_admin_can_delete_a_product_from_the_products_tab(): void
    {
        $admin = $this->makeAdmin();
        $product = $this->makeProduct();
        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'action' => 'updated',
            'details' => 'Earlier change to Test Laptop',
        ]);

        $response = $this->actingAs($admin)->delete('/admin/products/'.$product->id);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseHas('product_histories', [
            'user_id' => $admin->id,
            'action' => 'deleted',
        ]);
        $this->assertDatabaseHas('product_histories', [
            'user_id' => $admin->id,
            'product_id' => null,
            'details' => 'Earlier change to Test Laptop',
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'full_name' => 'Admin User',
            'email' => 'Admin@pc.lap',
            'password_hash' => Hash::make('Admin_123'),
            'role' => 'admin',
            'phone' => '0900000000',
        ]);
    }

    private function makeProduct(): Product
    {
        $brand = Brand::create(['name' => 'TestBrand']);
        $category = Category::create(['name' => 'Laptop']);

        return Product::create([
            'name' => 'Test Laptop',
            'sku' => 'SKU-'.uniqid(),
            'price' => 999.99,
            'stock_qty' => 3,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
    }
}
