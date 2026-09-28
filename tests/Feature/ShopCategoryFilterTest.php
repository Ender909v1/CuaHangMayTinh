<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCategoryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_category_filters_use_real_category_ids(): void
    {
        $brand = Brand::create(['name' => 'ASUS']);
        $category = Category::create(['name' => 'Gaming Desktops']);
        $product = Product::create([
            'name' => 'Gaming Tower',
            'sku' => 'GAMING-TOWER-001',
            'price' => 1599.99,
            'stock_qty' => 4,
            'type' => 'desktop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get('/shop');

        $response->assertOk();
        $response->assertSee('Gaming Desktops');
        $response->assertSee('data-filter-type="category" data-filter-value="'.$category->id.'"', false);
        $response->assertSee('data-category="'.$category->id.'"', false);
        $response->assertSee('.map(cb => cb.dataset.filterValue)', false);
        $response->assertSee('data-category="'.$product->category_id.'"', false);
        $response->assertDontSee('class="ml-2">Laptop</span>', false);
    }
}
