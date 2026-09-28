<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopOutOfStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_out_of_stock_item_displays_disabled_out_of_stock_button(): void
    {
        $brand = Brand::create(['name' => 'Test Brand']);
        $category = Category::create(['name' => 'Laptops']);

        $inStockProduct = Product::create([
            'name' => 'In Stock Laptop',
            'sku' => 'STOCK-IN-001',
            'price' => 15000000,
            'stock_qty' => 5,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $outOfStockProduct = Product::create([
            'name' => 'Sold Out Laptop',
            'sku' => 'STOCK-OUT-001',
            'price' => 20000000,
            'stock_qty' => 0,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get('/shop');

        $response->assertOk();

        // In-stock product has the working Add to Cart button
        $response->assertSee('data-add-to-cart data-product-id="'.$inStockProduct->id.'"', false);

        // Out-of-stock product does NOT have data-add-to-cart, has disabled "Out of Stock" button
        $response->assertDontSee('data-add-to-cart data-product-id="'.$outOfStockProduct->id.'"', false);
        $response->assertSee('Out of Stock', false);
        $response->assertSee('disabled', false);
        $response->assertSee('btn-out-of-stock', false);
    }
}
