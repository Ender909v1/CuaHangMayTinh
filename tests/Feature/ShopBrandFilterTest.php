<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopBrandFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_brand_filters_include_each_brand_with_active_products(): void
    {
        $category = Category::create(['name' => 'Components']);
        $brands = [
            Brand::create(['name' => 'ASUS']),
            Brand::create(['name' => 'Gigabyte']),
        ];

        foreach ($brands as $index => $brand) {
            Product::create([
                'name' => $brand->name.' Product',
                'sku' => 'BRAND-TEST-00'.($index + 1),
                'price' => 100,
                'stock_qty' => 5,
                'type' => 'component',
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'is_active' => true,
            ]);
        }

        $unusedBrand = Brand::create(['name' => 'Unused Brand']);
        $inactiveBrand = Brand::create(['name' => 'Inactive Brand']);
        Product::create([
            'name' => 'Inactive Product',
            'sku' => 'BRAND-TEST-INACTIVE',
            'price' => 100,
            'stock_qty' => 5,
            'type' => 'component',
            'brand_id' => $inactiveBrand->id,
            'category_id' => $category->id,
            'is_active' => false,
        ]);

        $response = $this->get('/shop');

        $response->assertOk();
        foreach ($brands as $brand) {
            $response->assertSee($brand->name);
            $response->assertSee('data-filter-type="brand" data-filter-value="'.$brand->id.'"', false);
            $response->assertSee('data-brand="'.$brand->id.'"', false);
        }
        $response->assertDontSee('data-filter-type="brand" data-filter-value="'.$unusedBrand->id.'"', false);
        $response->assertDontSee('data-filter-type="brand" data-filter-value="'.$inactiveBrand->id.'"', false);
        $response->assertDontSee('class="ml-2">Inactive Brand</span>', false);
    }
}
