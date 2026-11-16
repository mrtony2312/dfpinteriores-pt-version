<?php

namespace Tests\Feature;

use App\Http\Controllers\CheckoutController;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBankSettingTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(?User $admin = null): static
    {
        $admin ??= User::factory()->admin()->create();

        $this->actingAs($admin);

        return $this;
    }

    public function test_guest_is_redirected_from_bank_settings(): void
    {
        $this->get(route('admin.bank.edit'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_bank_settings(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.bank.edit'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_bank_settings_after_login(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.bank.edit'))
            ->assertOk();
    }

    public function test_admin_can_update_bank_details(): void
    {
        $this->actingAsAdmin()
            ->put(route('admin.bank.update'), [
                'bank_holder' => 'DFP Interiores Teste',
                'bank_iban' => 'PT50 1111 2222 3333 4444 5555 6',
                'bank_bic' => 'BBPIPTPL',
                'bank_name' => 'Banco Teste',
                'bank_nif' => '504074571',
            ])
            ->assertRedirect(route('admin.bank.edit'));

        $settings = SiteSetting::current();

        $this->assertSame('DFP Interiores Teste', $settings->bank_holder);
        $this->assertSame('PT50 1111 2222 3333 4444 5555 6', $settings->bank_iban);
        $this->assertSame('BBPIPTPL', $settings->bank_bic);
        $this->assertDatabaseHas('bank_setting_audits', ['action' => 'updated']);
    }

    public function test_admin_can_clear_bank_details(): void
    {
        SiteSetting::current()->update([
            'bank_holder' => 'Conta Temporária',
            'bank_iban' => 'PT50 1234 5678 9012 3456 7890 1',
            'bank_bic' => 'TESTPTPL',
            'bank_name' => 'Banco',
            'bank_nif' => '123',
        ]);

        $this->actingAsAdmin()
            ->delete(route('admin.bank.destroy'))
            ->assertRedirect(route('admin.bank.edit'));

        $settings = SiteSetting::current()->fresh();

        $this->assertNull($settings->bank_holder);
        $this->assertNull($settings->bank_iban);
        $this->assertSame('', CheckoutController::bankDetails()['iban']);
        $this->assertDatabaseHas('bank_setting_audits', ['action' => 'deleted']);
    }

    public function test_checkout_uses_database_bank_details(): void
    {
        SiteSetting::current()->update([
            'bank_holder' => 'Conta Site',
            'bank_iban' => 'PT50 9999 8888 7777 6666 5555 4',
            'bank_bic' => 'CGDIPTPL',
            'bank_name' => 'CGD',
            'bank_nif' => '504074571',
        ]);

        $details = CheckoutController::bankDetails();

        $this->assertSame('Conta Site', $details['holder']);
        $this->assertSame('PT50 9999 8888 7777 6666 5555 4', $details['iban']);
        $this->assertSame('CGDIPTPL', $details['bic']);
    }
}
