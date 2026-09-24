<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_access_dashboard(): void
    {
        User::create([
            'full_name' => 'Admin User',
            'email' => 'Admin@pc_lap',
            'password_hash' => Hash::make('Admin_123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'Admin@pc_lap',
            'password' => 'Admin_123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);

        $dashboard = $this->get('/admin');
        $dashboard->assertOk();
        $dashboard->assertSee('Admin Dashboard');
    }
}
