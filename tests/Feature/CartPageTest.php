<?php

namespace Tests\Feature;

use Tests\TestCase;

class CartPageTest extends TestCase
{
    public function test_cart_page_renders(): void
    {
        $this->get('/cart')->assertOk()->assertSee('Shopping Cart');
    }

    public function test_empty_state_is_centred_inside_the_cart_box(): void
    {
        $response = $this->get('/cart');

        $response->assertOk();
        $response->assertSee('Your cart is empty');
        // the empty message is both horizontally and vertically centred
        $response->assertSee('empty-cart-state', false);
        $response->assertSee('items-center justify-center', false);
        $response->assertSee('min-h-[18rem]', false);
    }

    public function test_cart_items_box_is_small_and_scrollable(): void
    {
        $response = $this->get('/cart');

        $response->assertOk();
        // fixed height + vertical scroll so adding many products does not stretch the page
        $response->assertSee('max-h-[22rem]', false);
        $response->assertSee('overflow-y-auto', false);
        $response->assertSee('id="cart-items"', false);
        $response->assertSee('id="cart-table"', false);
        $response->assertSee('id="empty-cart"', false);
    }
}
