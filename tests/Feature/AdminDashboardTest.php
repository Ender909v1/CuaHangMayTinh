<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
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

    public function test_admin_products_index_renders(): void
    {
        $admin = $this->makeAdmin();
        $this->makeProduct();

        $response = $this->actingAs($admin)->get('/admin/products');

        $response->assertOk();
        $response->assertSee('Test Laptop');
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
