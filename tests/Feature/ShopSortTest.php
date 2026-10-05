<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopSortTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_sort_dropdown_orders_products(): void
    {
        $brand = Brand::create(['name' => 'Sort Brand']);
        $category = Category::create(['name' => 'Sort Category']);

        $zebra = Product::create([
            'name' => 'Zebra Laptop',
            'sku' => 'SORT-ZEBRA',
            'price' => 100,
            'stock_qty' => 5,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'created_at' => now()->subDays(2),
        ]);
        $apple = Product::create([
            'name' => 'Apple Laptop',
            'sku' => 'SORT-APPLE',
            'price' => 100,
            'stock_qty' => 5,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'created_at' => now()->subDay(),
        ]);
        $mango = Product::create([
            'name' => 'Mango Laptop',
            'sku' => 'SORT-MANGO',
            'price' => 100,
            'stock_qty' => 5,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
            'created_at' => now(),
        ]);

        // A-Z sorting renders Apple -> Mango -> Zebra.
        $azResponse = $this->get('/shop?sort=az');
        $azResponse->assertOk();
        $azResponse->assertViewHas('sort', 'az');
        $this->assertProductOrder($azResponse->getContent(), [
            'Apple Laptop',
            'Mango Laptop',
            'Zebra Laptop',
        ]);

        // Latest sorting renders Mango (newest) -> Apple -> Zebra (oldest).
        $latestResponse = $this->get('/shop?sort=latest');
        $latestResponse->assertOk();
        $latestResponse->assertViewHas('sort', 'latest');
        $this->assertProductOrder($latestResponse->getContent(), [
            'Mango Laptop',
            'Apple Laptop',
            'Zebra Laptop',
        ]);

        // Popularity sorting renders the most-viewed product first.
        $apple->views()->create(['viewed_at' => now()]);
        $apple->views()->create(['viewed_at' => now()]);

        $popularResponse = $this->get('/shop?sort=popular');
        $popularResponse->assertOk();
        $popularResponse->assertViewHas('sort', 'popular');
        $this->assertSame($apple->id, $popularResponse->viewData('products')->first()->id);

        // The selected sort option stays selected in the dropdown.
        $popularResponse->assertSee('value="popular" selected', false);

        // Invalid sort values fall back to latest.
        $invalidResponse = $this->get('/shop?sort=invalid');
        $invalidResponse->assertOk();
        $invalidResponse->assertViewHas('sort', 'latest');
    }

    public function test_shop_no_longer_renders_removed_top_buttons(): void
    {
        $brand = Brand::create(['name' => 'Sort Brand']);
        $category = Category::create(['name' => 'Sort Category']);
        Product::create([
            'name' => 'Sortable Laptop',
            'sku' => 'SORT-ONLY',
            'price' => 100,
            'stock_qty' => 5,
            'type' => 'component',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $content = $this->get('/shop')->assertOk()->getContent();

        $this->assertStringNotContainsString('Show On Sale', $this->normalize($content));
        $this->assertStringNotContainsString('List View', $this->normalize($content));
        $this->assertStringNotContainsString('Grid View', $this->normalize($content));
        $this->assertStringContainsString('name="sort"', $content);
    }

    /**
     * @param  array<int, string>  $names
     */
    private function assertProductOrder(string $content, array $names): void
    {
        $normalized = $this->normalize($content);
        $positions = [];

        foreach ($names as $name) {
            $position = strpos($normalized, $name);
            $this->assertNotFalse($position, "Expected to find product '{$name}' on shop page.");
            $positions[] = $position;
        }

        $sorted = $positions;
        sort($sorted);
        $this->assertSame($sorted, $positions, 'Products are not rendered in the expected sort order.');
    }

    private function normalize(string $content): string
    {
        return (string) preg_replace('/\s+/', ' ', $content);
    }
}
