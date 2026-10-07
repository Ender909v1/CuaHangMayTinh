<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filters_products_by_name(): void
    {
        $this->makeCatalogue();

        $response = $this->get('/shop?search=gaming');

        $response->assertOk();
        $response->assertSee('Showing results for', false);
        $response->assertSee('Gaming Laptop', false);
        $response->assertDontSee('Mechanical Keyboard', false);
    }

    public function test_search_matches_sku(): void
    {
        $this->makeCatalogue();

        $response = $this->get('/shop?search=SKU-LAP');

        $response->assertOk();
        $response->assertSee('Gaming Laptop', false);
        $response->assertDontSee('Mechanical Keyboard', false);
    }

    public function test_search_matches_category_and_brand(): void
    {
        $this->makeCatalogue();

        $this->get('/shop?search=accessories')
            ->assertOk()
            ->assertSee('Mechanical Keyboard', false)
            ->assertDontSee('Gaming Laptop', false);

        $this->get('/shop?search=keychron')
            ->assertOk()
            ->assertSee('Mechanical Keyboard', false)
            ->assertDontSee('Gaming Laptop', false);
    }

    public function test_search_without_matches_shows_the_empty_state(): void
    {
        $this->makeCatalogue();

        $this->get('/shop?search=zzzz-no-such-product')
            ->assertOk()
            ->assertSee('No products found.', false)
            ->assertDontSee('Gaming Laptop', false)
            ->assertDontSee('Mechanical Keyboard', false);
    }

    public function test_header_renders_the_search_form_that_submits_to_the_shop(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('action="'.route('shop').'"', false)
            ->assertSee('name="search"', false);
    }

    public function test_active_search_is_preserved_by_the_sort_form_and_prefills_the_field(): void
    {
        $response = $this->get('/shop?search=gaming');

        $response->assertOk();
        $response->assertSee('<input type="hidden" name="search" value="gaming">', false);
        $response->assertSee('value="gaming"', false);
    }

    private function makeCatalogue(): void
    {
        $laptopBrand = Brand::create(['name' => 'ApexPC']);
        $accessoryBrand = Brand::create(['name' => 'Keychron']);
        $laptopCategory = Category::create(['name' => 'Laptops']);
        $accessoryCategory = Category::create(['name' => 'Accessories']);

        Product::create([
            'name' => 'Gaming Laptop',
            'sku' => 'SKU-LAP-001',
            'price' => 15000000,
            'stock_qty' => 5,
            'type' => 'laptop',
            'brand_id' => $laptopBrand->id,
            'category_id' => $laptopCategory->id,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Mechanical Keyboard',
            'sku' => 'SKU-KBD-001',
            'price' => 2000000,
            'stock_qty' => 5,
            'type' => 'accessory',
            'brand_id' => $accessoryBrand->id,
            'category_id' => $accessoryCategory->id,
            'is_active' => true,
        ]);
    }
}
