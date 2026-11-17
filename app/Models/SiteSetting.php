<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'bank_holder',
        'bank_iban',
        'bank_bic',
        'bank_name',
        'bank_nif',
        'bank_nib',
    ];

    /**
     * Singleton des réglages du site (créé à la volée avec les valeurs .env / config).
     */
    public static function current(): self
    {
        static::ensureBankColumns();

        $settings = static::query()->first();

        if ($settings !== null) {
            return $settings;
        }

        return static::query()->create([
            'bank_holder' => (string) config('checkout.bank.holder'),
            'bank_iban' => (string) config('checkout.bank.iban'),
            'bank_bic' => (string) config('checkout.bank.bic'),
            'bank_name' => (string) config('checkout.bank.bank_name'),
            'bank_nif' => (string) config('checkout.bank.nif'),
            'bank_nib' => (string) config('checkout.bank.nib'),
        ]);
    }

    /**
     * Ajoute les colonnes bancaires manquantes. Le site en ligne ne lance pas
     * toujours les migrations après un déploiement.
     */
    public static function ensureBankColumns(): void
    {
        if (! Schema::hasTable('site_settings') || Schema::hasColumn('site_settings', 'bank_nib')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('bank_nib', 32)->nullable();
        });
    }

    /**
     * Valeurs telles qu'enregistrées en admin (sans fallback .env après édition).
     *
     * @return array{holder: string, iban: string, bic: string, bank_name: string, nif: string, nib: string}
     */
    public function bankDetails(): array
    {
        return [
            'holder' => (string) ($this->bank_holder ?? ''),
            'iban' => (string) ($this->bank_iban ?? ''),
            'bic' => (string) ($this->bank_bic ?? ''),
            'bank_name' => (string) ($this->bank_name ?? ''),
            'nif' => (string) ($this->bank_nif ?? ''),
            'nib' => (string) ($this->bank_nib ?? ''),
        ];
    }

    public function clearBankDetails(): void
    {
        $this->update([
            'bank_holder' => null,
            'bank_iban' => null,
            'bank_bic' => null,
            'bank_name' => null,
            'bank_nif' => null,
            'bank_nib' => null,
        ]);
    }

    public function hasBankDetails(): bool
    {
        $details = $this->bankDetails();

        return $details['holder'] !== '' || $details['iban'] !== '' || $details['nib'] !== '';
    }
}
