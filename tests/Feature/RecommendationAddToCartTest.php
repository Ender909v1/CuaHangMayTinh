<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationAddToCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_the_recommendation_container(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('id="recommended-products"', false)
            ->assertSee('id="recommendation-list"', false);
    }

    public function test_recommendation_cards_render_wired_add_to_cart_buttons(): void
    {
        $script = file_get_contents(public_path('tailstore4-main/assets/js/script.js'));

        $this->assertIsString($script);

        // The recommendation template must carry the delegated click hook...
        $this->assertStringContainsString('style="margin-top: auto;" data-add-to-cart', $script);

        // ...plus every attribute the cart handler validates before adding.
        $this->assertStringContainsString('data-product-id="${product.productId}"', $script);
        $this->assertStringContainsString('data-product-name="${escapeText(product.name)}"', $script);
        $this->assertStringContainsString('data-product-image="${escapeText(product.image)}"', $script);
        $this->assertStringContainsString("closest('[data-add-to-cart]')", $script);
    }

    public function test_recommendation_source_products_expose_their_product_id(): void
    {
        $script = file_get_contents(public_path('tailstore4-main/assets/js/script.js'));

        $this->assertIsString($script);
        $this->assertStringContainsString('const productId = Number.parseInt(card.dataset.productId', $script);
        $this->assertStringContainsString("card.querySelector('[data-add-to-cart]')", $script);
    }
}
