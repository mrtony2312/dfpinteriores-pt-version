<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    /** Scénario A — visiteur → /admin */
    public function test_scenario_a_guest_cannot_open_admin_bank(): void
    {
        $this->get('/admin')->assertRedirect();
        $this->get('/admin/banco')->assertRedirect(route('admin.login'));
        $this->put('/admin/banco', ['bank_iban' => 'PT50 HACK'])->assertRedirect();
    }

    /** Scénario B — client connecté → admin */
    public function test_scenario_b_client_cannot_access_admin(): void
    {
        $client = User::factory()->create(['is_admin' => false]);

        $this->actingAs($client)
            ->get('/admin/banco')
            ->assertRedirect(route('admin.login'));
    }

    /** Scénario C — client tente de modifier l'IBAN */
    public function test_scenario_c_client_cannot_update_bank_via_http(): void
    {
        $client = User::factory()->create(['is_admin' => false]);

        $this->actingAs($client)
            ->put('/admin/banco', [
                'bank_holder' => 'Hacker',
                'bank_iban' => 'PT50 HACKED',
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('site_settings', ['bank_iban' => 'PT50 HACKED']);
    }

    /** Scénario D — CSRF n'est plus désactivé globalement (*) */
    public function test_scenario_d_csrf_not_globally_disabled(): void
    {
        $middleware = $this->app->make(\App\Http\Middleware\VerifyCsrfToken::class);
        $except = (new \ReflectionClass($middleware))->getProperty('except');
        $except->setAccessible(true);

        $this->assertNotContains('*', $except->getValue($middleware));
        $this->assertNotContains('admin/*', $except->getValue($middleware));
        $this->assertNotContains('admin/banco', $except->getValue($middleware));
    }

    /** Scénario E — IDOR commande d'un autre client */
    public function test_scenario_e_cannot_view_other_customer_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $order = Order::query()->create([
            'ref' => 'DFP-TEST-0001',
            'user_id' => $owner->id,
            'name' => 'Owner',
            'email' => $owner->email,
            'phone' => '900000000',
            'address' => 'Rua 1',
            'postal_code' => '1000-001',
            'city' => 'Lisboa',
            'billing_same' => true,
            'shipping_method' => 'ENTREGA',
            'shipping_cost' => 0,
            'payment_method' => 'VIREMENT',
            'subtotal' => 100,
            'total' => 100,
            'status' => 'AGUARDAR',
            'items_snapshot' => [],
        ]);

        $this->actingAs($intruder)
            ->get(route('checkout.success', $order->ref))
            ->assertForbidden();
    }

    /** Scénario F — pas d'API publique de modification bancaire */
    public function test_scenario_f_no_public_bank_api(): void
    {
        $this->postJson('/api/bank', ['iban' => 'x'])->assertNotFound();
        $this->putJson('/api/admin/banco', ['iban' => 'x'])->assertNotFound();
    }

    /** Scénario G — brute force admin limité */
    public function test_scenario_g_admin_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->from(route('admin.login'))
                ->post(route('admin.login.submit'), [
                    'email' => 'admin@dfpinteriores.com',
                    'password' => 'wrong-password-'.$i,
                ]);
        }

        $this->from(route('admin.login'))
            ->post(route('admin.login.submit'), [
                'email' => 'admin@dfpinteriores.com',
                'password' => 'wrong-password-final',
            ])
            ->assertSessionHasErrors('email');
    }

    /** Scénario H — impossible d'obtenir is_admin via registo */
    public function test_scenario_h_register_cannot_set_is_admin(): void
    {
        $this->postJson('/cliente/registo', [
            'nome' => 'Attaquant',
            'email' => 'attacker@example.com',
            'telemovel' => '912345678',
            'password' => 'Segura123',
            'is_admin' => true,
            'is_admin' => 1,
        ])->assertOk();

        $user = User::query()->where('email', 'attacker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse((bool) $user->is_admin);
    }

    /** Scénario I — path traversal images bloqué */
    public function test_scenario_i_image_path_traversal_blocked(): void
    {
        $this->get('/images/../../.env')->assertNotFound();
        $this->get('/images/..%2F..%2F.env')->assertNotFound();
    }

    /** Scénario J — statut commande non modifiable par client */
    public function test_scenario_j_client_cannot_patch_order_status(): void
    {
        $client = User::factory()->create();
        $order = Order::query()->create([
            'ref' => 'DFP-TEST-0002',
            'user_id' => $client->id,
            'name' => 'Client',
            'email' => $client->email,
            'phone' => '900000001',
            'address' => 'Rua 2',
            'postal_code' => '1000-002',
            'city' => 'Porto',
            'billing_same' => true,
            'shipping_method' => 'ENTREGA',
            'shipping_cost' => 0,
            'payment_method' => 'VIREMENT',
            'subtotal' => 150,
            'total' => 150,
            'status' => 'AGUARDAR',
            'items_snapshot' => [],
        ]);

        $this->actingAs($client)
            ->put('/orders/'.$order->id, ['status' => 'PAGO'])
            ->assertNotFound();

        $this->actingAs($client)
            ->patchJson('/api/orders/'.$order->id, ['status' => 'PAGO'])
            ->assertNotFound();

        $this->assertSame('AGUARDAR', $order->fresh()->status);
    }

    public function test_password_is_hashed_with_secure_algorithm(): void
    {
        $user = User::factory()->create(['password' => 'Segura123']);
        $this->assertTrue(Hash::check('Segura123', $user->password));
        $this->assertTrue(str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$argon2'));
    }
}
