<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AddToCartLoginGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_page_exposes_the_login_redirect_for_add_to_cart(): void
    {
        $response = $this->get('/shop');

        $response->assertOk();
        $response->assertSee('data-authenticated="0"', false);
        $response->assertSee('data-login-url="'.route('login').'"', false);
    }

    public function test_logged_in_page_allows_adding_to_cart(): void
    {
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->get('/shop');

        $response->assertOk();
        $response->assertSee('data-authenticated="1"', false);
        $response->assertSee('data-login-url="'.route('login').'"', false);
    }

    public function test_add_to_cart_script_redirects_guests_to_login(): void
    {
        $script = file_get_contents(public_path('tailstore4-main/assets/js/script.js'));

        $this->assertIsString($script);
        $this->assertStringContainsString("closest('[data-add-to-cart]')", $script);
        $this->assertStringContainsString("document.querySelector('header[data-authenticated]')", $script);
        $this->assertStringContainsString('window.location.href = storeHeader.dataset.loginUrl;', $script);
    }

    private function makeCustomer(): User
    {
        return User::create([
            'full_name' => 'Gate Customer',
            'email' => 'gate.customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
    }
}
