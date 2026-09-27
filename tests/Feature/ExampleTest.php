<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $brand = Brand::create(['name' => 'ExampleBrand']);
        $category = Category::create(['name' => 'ExampleCategory']);
        Product::create([
            'name' => 'Example Laptop',
            'sku' => 'EXAMPLE-'.uniqid(),
            'price' => 999.99,
            'stock_qty' => 5,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
