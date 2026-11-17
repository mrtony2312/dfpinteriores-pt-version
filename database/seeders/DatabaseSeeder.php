<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte admin + données bancaires checkout (rapide, toujours utile).
        $this->call(AdminUserSeeder::class);

        // Catalogue scrapé (../output/*.json + images) — optionnel / plus long.
        if ($this->command !== null && $this->command->confirm('Importer aussi le catalogue (catalog:import) ?', false)) {
            $this->command->call('catalog:import');
        }
    }
}
