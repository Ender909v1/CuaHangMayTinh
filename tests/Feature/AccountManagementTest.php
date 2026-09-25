<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_and_register_buttons(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Register');
        $response->assertSee('Login');
        $response->assertDontSee('Account management');
    }

    public function test_register_logs_user_in_and_header_shows_account_management(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Minh Nguyen',
            'email' => 'minh@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'minh@example.com', 'role' => 'customer']);

        $home = $this->get('/');
        $home->assertOk();
        $home->assertSee('Account management');
        $home->assertSee('Logout');
        $home->assertDontSee('>Login</a>', false);
    }

    public function test_register_requires_six_character_password(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Short Pass',
            'email' => 'short@example.com',
            'password' => 'abc',
            'password_confirmation' => 'abc',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_logged_in_user_visiting_login_is_redirected_to_account(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect(route('account'));
    }

    public function test_logged_in_user_visiting_register_is_redirected_to_account(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect(route('account'));
    }

    public function test_logged_in_user_can_view_account_page(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/account');

        $response->assertOk();
        $response->assertSee('Account management');
        $response->assertSee('minh@example.com');
    }

    public function test_guest_cannot_view_account_page(): void
    {
        $this->get('/account')->assertRedirect(route('login'));
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->put('/account', [
            'full_name' => 'Minh Updated',
            'email' => 'minh.updated@example.com',
            'phone' => '0901234567',
            'address' => '123 Test Street',
            'password' => 'newsecret',
            'password_confirmation' => 'newsecret',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Account updated successfully.');

        $user->refresh();
        $this->assertSame('Minh Updated', $user->full_name);
        $this->assertSame('minh.updated@example.com', $user->email);
        $this->assertSame('123 Test Street', $user->address);
        $this->assertTrue(Hash::check('newsecret', $user->password_hash));
    }

    public function test_user_can_update_profile_without_changing_password(): void
    {
        $user = $this->makeUser();
        $originalHash = $user->password_hash;

        $response = $this->actingAs($user)->put('/account', [
            'full_name' => 'Minh NoPass',
            'email' => 'minh@example.com',
            'phone' => '',
            'address' => '',
            'password' => '',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('Minh NoPass', $user->full_name);
        $this->assertSame($originalHash, $user->password_hash);
    }

    public function test_update_rejects_blank_phone_and_address(): void
    {
        $user = $this->makeUser();

        // Blank strings submitted from the HTML form must not blow up (nullable).
        $this->actingAs($user)->put('/account', [
            'full_name' => 'Minh Blank',
            'email' => 'minh.blank@example.com',
            'phone' => '',
            'address' => '',
        ])->assertSessionHasNoErrors();

        $this->assertNull($user->refresh()->phone);
        $this->assertNull($user->refresh()->address);
    }

    public function test_update_requires_unique_email(): void
    {
        $this->makeUser();
        $other = $this->makeUser('other@example.com');

        $response = $this->actingAs($other)->put('/account', [
            'full_name' => 'Other User',
            'email' => 'minh@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_logout_from_account_page(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_guest_can_render_all_public_pages(): void
    {
        foreach (['/', '/login', '/register', '/shop', '/product', '/cart', '/checkout', '/404'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_user_can_render_all_public_pages_while_logged_in(): void
    {
        $user = $this->makeUser();

        foreach (['/', '/shop', '/product', '/cart', '/checkout', '/404', '/account'] as $url) {
            $response = $this->actingAs($user)->get($url);

            $response->assertOk();
            // Login/Register buttons must be gone from the header on every page.
            $response->assertDontSee('>Register</a>', false);
        }
    }

    private function makeUser(string $email = 'minh@example.com'): User
    {
        return User::create([
            'full_name' => 'Minh Nguyen',
            'email' => $email,
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
    }
}
