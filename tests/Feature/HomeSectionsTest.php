<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_best_selling_products_as_popular_and_newest_as_latest(): void
    {
        $brand = Brand::create(['name' => 'Home Brand']);
        $category = Category::create(['name' => 'Home Category']);
        $user = User::create([
            'full_name' => 'Home Buyer',
            'email' => 'home.buyer@example.com',
            'password_hash' => 'secret',
        ]);

        $oldBestSeller = Product::create([
            'name' => 'Old Best Seller',
            'sku' => 'HOME-OLD-BEST',
            'price' => 100,
            'stock_qty' => 10,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'created_at' => now()->subDays(10),
        ]);
        $newest = Product::create([
            'name' => 'Newest Arrival',
            'sku' => 'HOME-NEWEST',
            'price' => 100,
            'stock_qty' => 10,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'created_at' => now(),
        ]);

        $order = Order::create([
            'bill_code' => 'HOME-0001',
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 500,
            'shipping_address' => '123 Home Street',
        ]);
        $order->items()->create([
            'product_id' => $oldBestSeller->id,
            'quantity' => 5,
            'unit_price' => 100,
        ]);
        $order->items()->create([
            'product_id' => $newest->id,
            'quantity' => 1,
            'unit_price' => 100,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('latestProducts', fn ($products): bool => $products->first()->id === $newest->id);
        $response->assertViewHas('popularProducts', fn ($products): bool => $products->first()->id === $oldBestSeller->id);

        $content = $response->getContent();
        $popularPosition = strpos($content, 'id="popular-products"');
        $latestPosition = strpos($content, 'id="latest-products"');
        $this->assertNotFalse($popularPosition);
        $this->assertNotFalse($latestPosition);

        $popularSection = substr($content, $popularPosition, $latestPosition - $popularPosition);
        $latestSection = substr($content, $latestPosition);

        $this->assertStringContainsString('Old Best Seller', $popularSection);
        $this->assertStringContainsString('Newest Arrival', $latestSection);
    }
}
