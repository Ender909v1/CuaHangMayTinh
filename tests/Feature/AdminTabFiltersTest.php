<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTabFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_page_filters_by_search_role_and_active(): void
    {
        $admin = $this->makeAdmin('admin-users@example.com');
        $this->makeUser('Alice Nguyen', 'alice@example.com', 'customer', true, '111');
        $this->makeUser('Bob Tran', 'bob@example.com', 'staff', false, '222');

        $this->actingAs($admin)->get('/admin/users?search=Alice')
            ->assertOk()
            ->assertSee('Alice Nguyen', false)
            ->assertDontSee('Bob Tran', false);

        $this->actingAs($admin)->get('/admin/users?role=staff')
            ->assertOk()
            ->assertSee('Bob Tran', false)
            ->assertDontSee('Alice Nguyen', false);

        $this->actingAs($admin)->get('/admin/users?active=no')
            ->assertOk()
            ->assertSee('Bob Tran', false)
            ->assertDontSee('Alice Nguyen', false);
    }

    public function test_dashboard_users_tab_supports_prefixed_filters(): void
    {
        $admin = $this->makeAdmin('admin-users-tab@example.com');
        $this->makeUser('Carol Pham', 'carol@example.com', 'customer', true, '333');
        $this->makeUser('Dan Le', 'dan@example.com', 'staff', true, '444');

        $this->actingAs($admin)->get('/admin?tab=users&u_search=Carol&u_role=customer&u_active=yes')
            ->assertOk()
            ->assertSee('Carol Pham', false)
            ->assertDontSee('Dan Le', false);
    }

    public function test_categories_page_filters_by_search_and_parent(): void
    {
        $admin = $this->makeAdmin('admin-cats@example.com');
        $parent = Category::create(['name' => 'Laptops Parent']);
        Category::create(['name' => 'Gaming Laptops Child', 'parent_id' => $parent->id]);
        Category::create(['name' => 'Keyboards Solo']);

        // Search narrows the result cards (dropdown still lists every name, so
        // assert on the card markup, not raw page text).
        $this->actingAs($admin)->get('/admin/categories?search=Gaming')
            ->assertOk()
            ->assertSee('<p class="text-lg font-bold text-gray-900">Gaming Laptops Child</p>', false)
            ->assertDontSee('<p class="text-lg font-bold text-gray-900">Keyboards Solo</p>', false);

        $this->actingAs($admin)->get('/admin/categories?parent=main')
            ->assertOk()
            ->assertSee('<p class="text-lg font-bold text-gray-900">Keyboards Solo</p>', false)
            ->assertDontSee('<p class="text-lg font-bold text-gray-900">Gaming Laptops Child</p>', false);

        $this->actingAs($admin)->get('/admin/categories?parent='.$parent->id)
            ->assertOk()
            ->assertSee('<p class="text-lg font-bold text-gray-900">Gaming Laptops Child</p>', false)
            ->assertDontSee('<p class="text-lg font-bold text-gray-900">Keyboards Solo</p>', false);
    }

    public function test_dashboard_categories_tab_supports_prefixed_filters(): void
    {
        $admin = $this->makeAdmin('admin-cats-tab@example.com');
        $parent = Category::create(['name' => 'Monitors Parent']);
        Category::create(['name' => 'Gaming Monitors Child', 'parent_id' => $parent->id]);

        $this->actingAs($admin)->get('/admin?tab=categories&c_search=Gaming&c_parent='.$parent->id)
            ->assertOk()
            ->assertSee('<p class="text-lg font-bold text-gray-900">Gaming Monitors Child</p>', false);
    }

    public function test_inventory_page_filters_by_search_and_stock(): void
    {
        $admin = $this->makeAdmin('admin-inv@example.com');
        $this->makeProduct('In Stock Laptop', 'INV-IN-001', 10);
        $this->makeProduct('Low Stock Mouse', 'INV-LOW-002', 3);
        $this->makeProduct('Out Of Stock Keyboard', 'INV-OUT-003', 0);

        $this->actingAs($admin)->get('/admin/inventory?search=INV-LOW-002')
            ->assertOk()
            ->assertSee('Low Stock Mouse', false)
            ->assertDontSee('In Stock Laptop', false);

        $this->actingAs($admin)->get('/admin/inventory?stock=low')
            ->assertOk()
            ->assertSee('Low Stock Mouse', false)
            ->assertDontSee('In Stock Laptop', false)
            ->assertDontSee('Out Of Stock Keyboard', false);

        $this->actingAs($admin)->get('/admin/inventory?stock=out')
            ->assertOk()
            ->assertSee('Out Of Stock Keyboard', false)
            ->assertDontSee('Low Stock Mouse', false);
    }

    public function test_dashboard_inventory_tab_supports_prefixed_filters(): void
    {
        $admin = $this->makeAdmin('admin-inv-tab@example.com');
        $this->makeProduct('ZZZ Dashboard Laptop Only', 'DASH-INV-001', 20);
        $this->makeProduct('QQQ Dashboard Cable Only', 'DASH-INV-002', 1);

        // The products tab is unfiltered, so assert on the SKU which only the
        // inventory rows render (products tab shows name/price/stock only).
        $this->actingAs($admin)->get('/admin?tab=inventory&i_search=DASH-INV-002&i_stock=low')
            ->assertOk()
            ->assertSee('DASH-INV-002', false)
            ->assertDontSee('DASH-INV-001', false);
    }

    public function test_dashboard_reviews_tab_filters_by_search_rating_and_answered(): void
    {
        $admin = $this->makeAdmin('admin-rev@example.com');
        $customer = $this->makeUser('Eve Ho', 'eve@example.com', 'customer', true, '555');
        $product = $this->makeProduct('Review Laptop', 'REV-001', 5);
        Review::create([
            'user_id' => $customer->id, 'product_id' => $product->id,
            'rating' => 5, 'comment' => 'Excellent laptop battery',
            'admin_response' => 'Thanks for the kind words!',
        ]);
        Review::create([
            'user_id' => $customer->id, 'product_id' => $product->id,
            'rating' => 2, 'comment' => 'Weak hinge design',
        ]);

        $this->actingAs($admin)->get('/admin?tab=reviews&r_search=battery&r_rating=5&r_answered=yes')
            ->assertOk()
            ->assertSee('Excellent laptop battery', false)
            ->assertDontSee('Weak hinge design', false);

        $this->actingAs($admin)->get('/admin?tab=reviews&r_answered=no')
            ->assertOk()
            ->assertSee('Weak hinge design', false)
            ->assertDontSee('Excellent laptop battery', false);
    }

    private function makeAdmin(string $email): User
    {
        return User::create([
            'full_name' => 'Admin', 'email' => $email,
            'password_hash' => Hash::make('Admin_123'), 'role' => 'admin',
        ]);
    }

    private function makeUser(string $name, string $email, string $role, bool $active, string $phone): User
    {
        return User::create([
            'full_name' => $name, 'email' => $email,
            'password_hash' => Hash::make('secret123'), 'role' => $role,
            'is_active' => $active, 'phone' => $phone,
        ]);
    }

    private function makeProduct(string $name, string $sku, int $stock): Product
    {
        $brand = Brand::create(['name' => 'Brand for '.$name]);
        $category = Category::create(['name' => 'Category for '.$name]);

        return Product::create([
            'name' => $name, 'sku' => $sku, 'price' => 1000,
            'stock_qty' => $stock, 'type' => 'physical',
            'brand_id' => $brand->id, 'category_id' => $category->id,
        ]);
    }
}
