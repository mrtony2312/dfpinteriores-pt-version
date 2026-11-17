@extends('layouts.app')

@section('title', 'Finalizar encomenda | DFP Interiores')

@section('content')
<div class="CheckoutPage">
    <h1>Finalizar encomenda</h1>
    <p class="co-intro">Preencha os seus dados, efetue a transferência bancária e anexe o comprovativo para concluir.</p>

    @if ($errors->any())
        <div class="co-errors">
            <strong>Não foi possível concluir. Corrija os campos assinalados:</strong>
            <ul style="margin:6px 0 0 18px;padding:0;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="payment_method" value="VIREMENT">

        <div class="co-grid">
            <div>
                <div class="co-card">
                    <h2><span class="co-step">1</span> Contacto e entrega</h2>
                    <div class="grid2">
                        <div class="field">
                            <label>Nome completo</label>
                            <input type="text" name="name" value="{{ $prefill['name'] }}" class="@error('name') is-invalid @enderror" required>
                            @error('name') <span class="err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label>Telemóvel</label>
                            <input type="tel" name="phone" value="{{ $prefill['phone'] }}" class="@error('phone') is-invalid @enderror" required>
                            @error('phone') <span class="err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $prefill['email'] }}" class="@error('email') is-invalid @enderror" required>
                            @error('email') <span class="err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label>NIF (opcional)</label>
                            <input type="text" name="nif" value="{{ old('nif') }}" class="@error('nif') is-invalid @enderror">
                            @error('nif') <span class="err">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="field" style="margin-top:12px;">
                        <label>Morada de entrega</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="@error('address') is-invalid @enderror" required>
                        @error('address') <span class="err">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <label>Código postal <span style="text-transform:none;font-weight:400;color:#999;">(opcional)</span></label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="0000-000" class="@error('postal_code') is-invalid @enderror">
                            @error('postal_code') <span class="err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label>Localidade</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="@error('city') is-invalid @enderror" required>
                            @error('city') <span class="err">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label>Notas (opcional)</label>
                        <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes') <span class="err">{{ $message }}</span> @enderror
                    </div>

                    <label class="check-row" style="margin-top:10px;">
                        <input type="checkbox" name="billing_different" value="1" id="billingDiff"
                               {{ old('billing_different') ? 'checked' : '' }}>
                        <span>A morada de faturação é diferente da morada de entrega</span>
                    </label>
                </div>

                <div class="co-card" id="billingCard" @if(! old('billing_different')) hidden @endif>
                    <h2>Morada de faturação</h2>
                    <div class="field">
                        <label>Nome / Empresa</label>
                        <input type="text" name="billing_name" value="{{ old('billing_name') }}" class="@error('billing_name') is-invalid @enderror">
                        @error('billing_name') <span class="err">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label>Morada</label>
                        <input type="text" name="billing_address" value="{{ old('billing_address') }}" class="@error('billing_address') is-invalid @enderror">
                        @error('billing_address') <span class="err">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <label>Código postal <span style="text-transform:none;font-weight:400;color:#999;">(opcional)</span></label>
                            <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" placeholder="0000-000" class="@error('billing_postal_code') is-invalid @enderror">
                            @error('billing_postal_code') <span class="err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label>Localidade</label>
                            <input type="text" name="billing_city" value="{{ old('billing_city') }}" class="@error('billing_city') is-invalid @enderror">
                            @error('billing_city') <span class="err">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="co-card">
                    <h2><span class="co-step">2</span> Pagamento por transferência bancária</h2>
                    <p class="co-bank-lead">Único método de pagamento: transfira o total da encomenda para a conta abaixo e anexe o comprovativo.</p>

                    <div class="co-bank">
                        <div class="co-bank-title">Dados da conta DFP Interiores</div>
                        @if ($bank['holder'] || $bank['iban'])
                            @if ($bank['holder'])
                                <div class="co-bank-row"><span>Titular</span><strong>{{ $bank['holder'] }}</strong></div>
                            @endif
                            @if ($bank['bank_name'])
                                <div class="co-bank-row"><span>Banco</span><strong>{{ $bank['bank_name'] }}</strong></div>
                            @endif
                            @if ($bank['iban'])
                                <div class="co-bank-row">
                                    <span>IBAN</span>
                                    <strong class="iban">{{ $bank['iban'] }}</strong>
                                </div>
                            @endif
                            @if ($bank['bic'])
                                <div class="co-bank-row"><span>BIC/SWIFT</span><strong>{{ $bank['bic'] }}</strong></div>
                            @endif
                            @if ($bank['nif'])
                                <div class="co-bank-row"><span>NIF</span><strong>{{ $bank['nif'] }}</strong></div>
                            @endif
                        @else
                            <p class="co-bank-hint">Os dados bancários serão confirmados pela nossa equipa após a encomenda.</p>
                        @endif
                        <p class="co-bank-hint">Use a referência da encomenda no descritivo da transferência (será indicada no email de confirmação).</p>
                    </div>

                    <div class="co-proof">
                        <label class="co-proof-label" for="transfer_proof">3 — Anexar comprovativo de pagamento</label>
                        <input type="file" name="transfer_proof" id="transfer_proof" accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf" required
                               class="@error('transfer_proof') is-invalid @enderror">
                        <p class="co-proof-hint">JPG, PNG, WEBP ou PDF — máx. 5 MB. O comprovativo será enviado por email com os detalhes da encomenda.</p>
                        <p class="co-proof-name" id="transferProofName" hidden></p>
                        @error('transfer_proof') <span class="err">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <aside class="co-summary">
                <h2>A sua encomenda</h2>
                @foreach ($cart['items'] as $it)
                    <div class="li">
                        <img src="/images/120-120/{{ $it['images'][0] }}" alt="{{ $it['nome'] }}">
                        <div>
                            <div class="nm">{{ $it['nome'] }}</div>
                            <div class="q">Qtd: {{ $it['qtd'] }}</div>
                        </div>
                        <div class="pr">{{ number_format((float) $it['total'], 2, ',', ' ') }}€</div>
                    </div>
                @endforeach

                <div class="row"><span>Subtotal</span><span>{{ number_format((float) $cart['subtotal'], 2, ',', ' ') }}€</span></div>
                <div class="row"><span>Portes de envio</span><span>Grátis</span></div>
                <div class="row total"><span>Total a transferir</span><span>{{ number_format((float) $cart['subtotal'], 2, ',', ' ') }}€</span></div>
                <p style="font-size:13px;color:#555;margin:8px 0 14px;">
                    Envio gratuito para todo o Portugal.<br>
                    Entrega em 1 a 3 dias úteis (preparação: 1 dia útil).
                </p>

                <label class="check-row">
                    <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <span>Li e aceito os <a href="{{ route('ajuda.termos') }}" target="_blank">termos e condições</a>.</span>
                </label>
                @error('terms') <span class="err">{{ $message }}</span> @enderror

                <button type="submit" class="co-submit">Confirmar encomenda</button>
                <p class="co-submit-hint">Ao confirmar, recebe por email o resumo da encomenda e o comprovativo em anexo.</p>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<style>
.CheckoutPage .co-intro { color:#555; margin:0 0 18px; max-width:720px; }
.CheckoutPage .co-step {
    display:inline-flex; align-items:center; justify-content:center;
    width:1.5rem; height:1.5rem; margin-right:.4rem; border-radius:999px;
    background:#e5007d; color:#fff; font-size:.85rem; font-weight:700;
}
.CheckoutPage .co-bank-lead { margin:0 0 12px; color:#444; font-size:14px; }
.CheckoutPage .co-submit-hint { margin:10px 0 0; font-size:12px; color:#666; line-height:1.4; }
</style>
<script>
(function () {
    var billingDiff = document.getElementById('billingDiff');
    var billingCard = document.getElementById('billingCard');
    if (billingDiff && billingCard) {
        billingDiff.addEventListener('change', function () {
            billingCard.hidden = !billingDiff.checked;
        });
    }
    var proof = document.getElementById('transfer_proof');
    var proofName = document.getElementById('transferProofName');
    if (proof && proofName) {
        proof.addEventListener('change', function () {
            var file = proof.files && proof.files[0];
            if (file) {
                proofName.hidden = false;
                proofName.textContent = 'Ficheiro: ' + file.name;
            } else {
                proofName.hidden = true;
                proofName.textContent = '';
            }
        });
    }
})();
</script>
@endpush
