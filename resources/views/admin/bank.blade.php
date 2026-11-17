@extends('layouts.admin')

@section('title', 'Dados bancários')

@section('content')
<div class="card">
    <h1>Dados bancários</h1>
    <p class="lead">
        Estes dados aparecem no checkout e nos emails de encomenda quando o cliente escolhe transferência bancária.
    </p>

    @if ($errors->any())
        <div class="alert alert-err">
            <ul style="margin:0;padding-left:1.1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('admin.bank.update') }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="bank_holder">Titular</label>
            <input id="bank_holder" name="bank_holder" value="{{ old('bank_holder', $settings->bank_holder) }}">
        </div>
        <div class="field">
            <label for="bank_iban">IBAN</label>
            <input id="bank_iban" name="bank_iban" value="{{ old('bank_iban', $settings->bank_iban) }}">
        </div>
        <div class="field">
            <label for="bank_bic">BIC / SWIFT</label>
            <input id="bank_bic" name="bank_bic" value="{{ old('bank_bic', $settings->bank_bic) }}">
        </div>
        <div class="field">
            <label for="bank_name">Banco</label>
            <input id="bank_name" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}">
        </div>
        <div class="field">
            <label for="bank_nif">NIF</label>
            <input id="bank_nif" name="bank_nif" value="{{ old('bank_nif', $settings->bank_nif) }}">
        </div>
        <div class="actions">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

    <form method="post" action="{{ route('admin.bank.destroy') }}" onsubmit="return confirm('Eliminar os dados bancários do site?');" style="margin-top:1rem;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Limpar dados bancários</button>
    </form>

    <p class="hint">Alterações ficam visíveis de imediato em <a href="{{ route('checkout') }}">/carrinho/checkout</a>.</p>
</div>

@if ($audits->isNotEmpty())
<div class="card" style="margin-top:1.25rem;">
    <h1 style="font-size:1.1rem;">Histórico recente</h1>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Ação</th>
                <th>Utilizador</th>
                <th>Campos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($audits as $audit)
                <tr>
                    <td>{{ $audit->created_at?->format('Y-m-d H:i') }}</td>
                    <td>{{ $audit->action }}</td>
                    <td>{{ $audit->user?->email ?? '—' }}</td>
                    <td><code>{{ implode(', ', $audit->changed_fields ?? []) }}</code></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
