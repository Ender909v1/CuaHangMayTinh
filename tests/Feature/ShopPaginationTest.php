<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_shows_twenty_active_products_per_page(): void
    {
        $brand = Brand::create(['name' => 'Test Brand']);
        $category = Category::create(['name' => 'Test Category']);
        $productIds = [];

        for ($index = 1; $index <= 21; $index++) {
            $product = Product::create([
                'name' => 'Shop Product '.$index,
                'sku' => 'SHOP-PAGE-'.str_pad((string) $index, 3, '0', STR_PAD_LEFT),
                'price' => 100,
                'stock_qty' => 5,
                'type' => 'component',
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'is_active' => true,
                'created_at' => now()->addSeconds($index),
            ]);
            $productIds[] = $product->id;
        }

        $firstPage = $this->get('/shop');

        $firstPage->assertOk();
        $firstPage->assertSee('page=2', false);
        $firstPage->assertViewHas('products', fn ($products): bool => $products->count() === 20
            && $products->first()->id === $productIds[20]
            && $products->last()->id === $productIds[1]);
        $this->assertSame(20, substr_count($firstPage->getContent(), 'data-add-to-cart data-product-id='));

        $secondPage = $this->get('/shop?page=2');

        $secondPage->assertOk();
        $secondPage->assertViewHas('products', fn ($products): bool => $products->count() === 1
            && $products->first()->id === $productIds[0]);
        $this->assertSame(1, substr_count($secondPage->getContent(), 'data-add-to-cart data-product-id='));
    }
}
