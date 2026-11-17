<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_redirects_to_bank_without_two_factor(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.bank.edit'));

        $this->assertAuthenticatedAs($admin);
        $this->assertFalse(session()->has('admin_2fa_passed'));
    }

    public function test_two_factor_routes_are_removed(): void
    {
        $this->get('/admin/2fa')->assertNotFound();
        $this->post('/admin/2fa')->assertNotFound();
    }
}
