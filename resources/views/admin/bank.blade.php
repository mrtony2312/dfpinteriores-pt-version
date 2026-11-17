@extends('layouts.admin')

@section('title', 'Dados bancários')

@section('content')
    <div class="panel">
        <h1>Dados bancários</h1>
        <p class="lead">Modifique ou elimine estas informações a qualquer momento. As alterações são imediatas no checkout e nos emails.</p>

        @if (session('success'))
            <div class="alert alert-ok">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('admin.bank.update') }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="bank_holder">Titular da conta</label>
                <input id="bank_holder" type="text" name="bank_holder" value="{{ old('bank_holder', $settings->bank_holder) }}" placeholder="Ex.: DFP Interiores, Unipessoal Lda">
            </div>

            <div class="field">
                <label for="bank_iban">IBAN</label>
                <input id="bank_iban" type="text" name="bank_iban" value="{{ old('bank_iban', $settings->bank_iban) }}" placeholder="PT50 …">
            </div>

            <div class="field">
                <label for="bank_bic">BIC / SWIFT</label>
                <input id="bank_bic" type="text" name="bank_bic" value="{{ old('bank_bic', $settings->bank_bic) }}">
            </div>

            <div class="field">
                <label for="bank_name">Nome do banco</label>
                <input id="bank_name" type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}">
            </div>

            <div class="field">
                <label for="bank_nif">NIF</label>
                <input id="bank_nif" type="text" name="bank_nif" value="{{ old('bank_nif', $settings->bank_nif) }}">
            </div>

            <div class="actions">
                <button type="submit" class="btn">Guardar alterações</button>
            </div>
        </form>

        <form method="post" action="{{ route('admin.bank.destroy') }}" class="danger-zone"
              onsubmit="return confirm('Eliminar todos os dados bancários do site?');">
            @csrf
            @method('DELETE')
            <p>Para remover completamente as informações bancárias apresentadas no site:</p>
            <button type="submit" class="btn btn-danger">Eliminar dados bancários</button>
        </form>

        <p class="hint">Pré-visualizar no site: <a href="{{ url('/carrinho/checkout') }}" target="_blank" rel="noopener">página de checkout</a></p>
    </div>

    @if ($audits->isNotEmpty())
        <div class="panel" style="margin-top:1.25rem;">
            <h1>Histórico de alterações</h1>
            <p class="lead">Registo das últimas modificações (sem palavras-passe nem segredos).</p>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.88rem;">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid #e4e0d8;">
                            <th style="padding:0.5rem;">Data</th>
                            <th style="padding:0.5rem;">Admin</th>
                            <th style="padding:0.5rem;">Ação</th>
                            <th style="padding:0.5rem;">Campos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:0.5rem;white-space:nowrap;">{{ $audit->created_at?->format('d/m/Y H:i') }}</td>
                                <td style="padding:0.5rem;">{{ $audit->user?->email ?? '—' }}</td>
                                <td style="padding:0.5rem;">{{ $audit->action }}</td>
                                <td style="padding:0.5rem;">{{ implode(', ', $audit->changed_fields ?? []) ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
