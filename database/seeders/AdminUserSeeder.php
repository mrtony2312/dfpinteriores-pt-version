<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrNew([
            'email' => env('ADMIN_EMAIL', 'admin@dfpinteriores.com'),
        ]);

        $user->fill([
            'name' => 'Administrador',
            'password' => env('ADMIN_PASSWORD', 'AdminDFP2026!'),
        ]);
        $user->save();
        $user->grantAdmin();

        SiteSetting::current();

        if ($this->command !== null) {
            $this->command->info('Admin prêt : '.$user->email);
            $this->command->info('Login : /admin/login  →  Dados bancários : /admin/banco');
        }
    }
}
