@extends('layouts.omniva')

@section('title', 'Encomenda registada | DFP Interiores')

@push('styles')
<link rel="stylesheet" href="/assets/css/checkout-omniva.css?v=9">
@endpush

@section('content')
@php
    $fmt = fn ($value) => number_format((float) $value, 2, ',', ' ').' €';
@endphp

<div class="tf-page-title-2 flat-spacing-2">
    <div class="container">
        <div class="content">
            <ul class="breadcrumb h5 fs-15">
                <li>
                    <a href="{{ route('home') }}" class="link-underline">INÍCIO</a>
                </li>
                <li>/</li>
                <li>ENCOMENDA</li>
            </ul>
            <h3 class="text-center lh_100">Encomenda {{ $order->ref }}</h3>
        </div>
    </div>
</div>

<div class="flat-spacing pt-0">
    <div class="container">
        <div class="row gy-30">
            <div class="col-lg-8">
                <div class="tf-checkout-cart-main">
                    <div class="box-ip-payment">
                        <div class="title">
                            <h6 class="mb-4">Faça a transferência de imediato</h6>
                            <p class="text-caption cl-text-4">
                                A encomenda de {{ $order->name }} está registada. Transfira agora
                                <strong>{{ $fmt($order->total) }}</strong> para a conta abaixo.
                                No descritivo, indique a referência <strong>{{ $order->ref }}</strong>.
                            </p>
                        </div>

                        <div class="dfp-co-bank mb-16">
                            @if ($bank['holder'] || $bank['iban'])
                                @if ($bank['holder'])
                                    <div class="dfp-co-bank-row"><span>Titular</span><strong>{{ $bank['holder'] }}</strong></div>
                                @endif
                                @if ($bank['bank_name'])
                                    <div class="dfp-co-bank-row"><span>Banco</span><strong>{{ $bank['bank_name'] }}</strong></div>
                                @endif
                                @if ($bank['iban'])
                                    <div class="dfp-co-bank-row"><span>IBAN</span><strong class="iban">{{ $bank['iban'] }}</strong></div>
                                @endif
                                @if ($bank['bic'])
                                    <div class="dfp-co-bank-row"><span>BIC/SWIFT</span><strong>{{ $bank['bic'] }}</strong></div>
                                @endif
                                @if ($bank['nif'])
                                    <div class="dfp-co-bank-row"><span>NIF</span><strong>{{ $bank['nif'] }}</strong></div>
                                @endif
                            @else
                                <p class="text-caption mb-0">Os dados da conta serão enviados pela nossa equipa. Faça a transferência assim que os receber.</p>
                            @endif
                            <div class="dfp-co-bank-row"><span>Montante</span><strong>{{ $fmt($order->total) }}</strong></div>
                            <div class="dfp-co-bank-row"><span>Descritivo</span><strong>{{ $order->ref }}</strong></div>
                        </div>

                        <div class="dfp-co-proof-block">
                            <div class="dfp-co-proof-block__head">
                                <span class="dfp-co-proof-block__step">2</span>
                                <div>
                                    <h6 class="mb-4">Comprovativo de transferência</h6>
                                    <p class="text-caption cl-text-4 mb-0">Depois do depósito, anexe o recibo do banco. Confirmamos o pagamento e preparamos a encomenda.</p>
                                </div>
                            </div>

                            @if (session('proof_sent') || $order->transfer_proof_name)
                                <div class="dfp-co-proof-ok">
                                    <strong>Comprovativo recebido</strong>
                                    <span>{{ $order->transfer_proof_name ?: 'Ficheiro enviado' }}. A equipa confirma o pagamento. Pode substituir o ficheiro se precisar.</span>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('checkout.proof', $order->ref) }}" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="dfp-co-errors mb-16">
                                        <ul>
                                            @foreach ($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="dfp-co-proof @error('transfer_proof') is-invalid @enderror" id="transferProofZone">
                                    <span class="dfp-co-proof__icon" aria-hidden="true">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 16V5M12 5l-4 4M12 5l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4 16.5V18a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="dfp-co-proof__title">Arraste o recibo para aqui</span>
                                    <span class="dfp-co-proof__hint">JPG, PNG, WEBP ou PDF — máx. 5 MB</span>
                                    <button type="button" class="tf-btn-line text-caption" id="transferProofPick">Escolher comprovativo</button>
                                    <span class="dfp-co-proof__name" id="transferProofName">Nenhum ficheiro escolhido</span>
                                    <ul class="dfp-co-proof__types">
                                        <li>JPG</li>
                                        <li>PNG</li>
                                        <li>WEBP</li>
                                        <li>PDF</li>
                                    </ul>
                                    <input class="dfp-co-file" type="file" name="transfer_proof" id="transfer_proof" accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf" aria-labelledby="transferProofPick">
                                </div>
                                @error('transfer_proof') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                <button type="submit" class="tf-btn animate-btn size3 rounded-6 w-100 dfp-co-proof__submit">Enviar comprovativo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="tf-page-cart-sidebar sticky-top">
                    <div class="cart-box order-box">
                        <h6 class="title cl-text-main">A sua encomenda</h6>
                        <ul class="list-total cl-text-main">
                            @foreach ($order->items as $it)
                                <li class="total-item text-caption d-flex justify-content-between">
                                    <span>{{ $it->name }} × {{ $it->qty }}</span>
                                    <span class="fw-medium">{{ $fmt($it->line_total) }}</span>
                                </li>
                            @endforeach
                            <li class="total-item text-caption d-flex justify-content-between">
                                <span>Portes</span>
                                <span class="fw-medium">{{ $order->shipping_cost > 0 ? $fmt($order->shipping_cost) : 'Grátis' }}</span>
                            </li>
                        </ul>
                        <div class="subtotal h6 d-flex justify-content-between cl-text-main">
                            <span>Total:</span>
                            <span>{{ $fmt($order->total) }}</span>
                        </div>
                        <p class="text-caption cl-text-4 mt-16 mb-0">
                            Entrega: {{ $order->address }}, {{ $order->postal_code }} {{ $order->city }}
                        </p>
                        <a href="{{ route('home') }}" class="tf-btn-line text-caption mt-16">Continuar a comprar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-omniva.similar-products :products="$similarProducts" />
@endsection

@push('scripts')
<script>
(function () {
    var zone = document.getElementById('transferProofZone');
    var proof = document.getElementById('transfer_proof');
    var proofName = document.getElementById('transferProofName');
    var pick = document.getElementById('transferProofPick');
    var form = proof ? proof.form : null;
    if (!zone || !proof || !proofName || !pick) return;

    var sizeLabel = function (bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return Math.max(1, Math.round(bytes / 1024)) + ' KB';
        return (bytes / 1048576).toFixed(1).replace('.', ',') + ' MB';
    };

    var showFile = function (file) {
        if (!file) {
            proofName.textContent = 'Nenhum ficheiro escolhido';
            zone.classList.remove('is-ready');
            return;
        }
        proofName.textContent = file.name + ' · ' + sizeLabel(file.size);
        zone.classList.add('is-ready');
        zone.classList.remove('is-invalid');
    };

    var openPicker = function () { proof.click(); };

    pick.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        openPicker();
    });
    zone.addEventListener('click', function (event) {
        if (event.target.closest('button')) return;
        openPicker();
    });
    zone.addEventListener('dragover', function (event) {
        event.preventDefault();
        zone.classList.add('is-drag');
    });
    zone.addEventListener('dragleave', function () {
        zone.classList.remove('is-drag');
    });
    zone.addEventListener('drop', function (event) {
        event.preventDefault();
        zone.classList.remove('is-drag');
        var file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
        if (!file) return;
        var transfer = new DataTransfer();
        transfer.items.add(file);
        proof.files = transfer.files;
        showFile(file);
    });
    proof.addEventListener('change', function () {
        showFile(proof.files && proof.files[0]);
    });
    form?.addEventListener('submit', function (event) {
        if (proof.files && proof.files.length) return;
        event.preventDefault();
        zone.classList.add('is-invalid');
        proofName.textContent = 'Anexe o comprovativo da transferência bancária.';
    });
})();
</script>
@endpush
