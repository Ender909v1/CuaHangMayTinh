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
use Illuminate\Support\Facades\Hash;
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

