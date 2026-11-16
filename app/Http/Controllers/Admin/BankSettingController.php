<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSettingAudit;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankSettingController extends Controller
{
    public function edit()
    {
        return view('admin.bank', [
            'settings' => SiteSetting::current(),
            'audits' => BankSettingAudit::query()
                ->with('user:id,name,email')
                ->latest()
                ->limit(20)
                ->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'bank_holder' => ['nullable', 'string', 'max:255'],
            'bank_iban' => ['nullable', 'string', 'max:64'],
            'bank_bic' => ['nullable', 'string', 'max:32'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_nif' => ['nullable', 'string', 'max:32'],
        ]);

        foreach ($data as $key => $value) {
            $data[$key] = is_string($value) ? trim($value) : $value;
            if ($data[$key] === '') {
                $data[$key] = null;
            }
        }

        $settings = SiteSetting::current();
        $before = $settings->bankDetails();

        $settings->update($data);

        $after = $settings->fresh()->bankDetails();
        $this->audit($request, 'updated', $before, $after);

        return redirect()
            ->route('admin.bank.edit')
            ->with('success', 'Dados bancários atualizados. Já estão visíveis no checkout.');
    }

    public function destroy(Request $request)
    {
        $settings = SiteSetting::current();
        $before = $settings->bankDetails();

        $settings->clearBankDetails();

        $after = $settings->fresh()->bankDetails();
        $this->audit($request, 'deleted', $before, $after);

        return redirect()
            ->route('admin.bank.edit')
            ->with('success', 'Dados bancários eliminados. Já não aparecem no site.');
    }

    /**
     * @param  array{holder: string, iban: string, bic: string, bank_name: string, nif: string}  $before
     * @param  array{holder: string, iban: string, bic: string, bank_name: string, nif: string}  $after
     */
    private function audit(Request $request, string $action, array $before, array $after): void
    {
        $changed = [];
        foreach ($after as $field => $value) {
            if (($before[$field] ?? null) !== $value) {
                $changed[] = $field;
            }
        }

        BankSettingAudit::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'before' => $before,
            'after' => $after,
            'changed_fields' => $changed,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500),
        ]);
    }
}
