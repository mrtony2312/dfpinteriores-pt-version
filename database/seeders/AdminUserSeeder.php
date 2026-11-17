<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@dfpinteriores.com');
        $password = (string) env('ADMIN_PASSWORD', 'AdminDFP2026!');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin DFP',
                'password' => Hash::make($password),
                'is_admin' => true,
            ],
        );

        // Données bancaires du checkout (modifiables ensuite via /admin/banco).
        $settings = SiteSetting::query()->first();

        if ($settings === null) {
            SiteSetting::query()->create([
                'bank_holder' => (string) env('BANK_ACCOUNT_HOLDER', config('checkout.bank.holder')),
                'bank_iban' => (string) env('BANK_IBAN', config('checkout.bank.iban')),
                'bank_bic' => (string) env('BANK_BIC', config('checkout.bank.bic')),
                'bank_name' => (string) env('BANK_NAME', config('checkout.bank.bank_name')),
                'bank_nif' => (string) env('BANK_NIF', config('checkout.bank.nif')),
            ]);
        }

        if ($this->command !== null) {
            $this->command->info("Admin prêt : {$email}");
            $this->command->info('Login : /admin/login  →  Dados bancários : /admin/banco');
        }
    }
}
