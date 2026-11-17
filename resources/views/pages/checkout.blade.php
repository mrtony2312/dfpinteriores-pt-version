@extends('layouts.omniva')

@section('title', 'Finalizar encomenda | DFP Interiores')
@section('meta_description', 'Finalize a sua encomenda na DFP Interiores. Envio gratuito em todo o Portugal.')

@push('styles')
<link rel="stylesheet" href="/assets/css/checkout-omniva.css?v=3">
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
@php
    $fmt = fn ($v) => number_format((float) $v, 2, ',', ' ').'€';
@endphp

<div class="tf-page-title-2 flat-spacing-2">
    <div class="container">
        <div class="content">
            <ul class="breadcrumb h5 fs-15">
                <li><a href="{{ route('home') }}" class="link-underline">INÍCIO</a></li>
                <li>/</li>
                <li><a href="{{ route('carrinho') }}" class="link-underline">CARRINHO</a></li>
                <li>/</li>
                <li>CHECKOUT</li>
            </ul>
            <h3 class="text-center lh_100">Finalizar encomenda</h3>
        </div>
    </div>
</div>

<<<<<<< Updated upstream
<div class="flat-spacing pt-0">
    <div class="container">
        @if ($errors->any())
            <div class="dfp-co-errors mb-20">
                <strong>Não foi possível concluir. Corrija os campos assinalados:</strong>
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm" enctype="multipart/form-data">
            @csrf
            <div class="row gy-30">
                <div class="col-lg-8">
                    <div class="tf-checkout-cart-main">
                        <div class="box-ip-contact">
                            <div class="title">
                                <div class="text-lg fw-medium cl-text-main">Informação de contacto</div>
                                <a href="{{ route('cliente') }}" class="tf-btn-line text-caption">Conta</a>
                            </div>
                            <div class="form-content gap-15">
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('name') is-invalid @enderror"
                                               id="name" type="text" name="name" value="{{ $prefill['name'] }}"
                                               placeholder=" " required>
                                        <label class="tf-field-label" for="name">Nome completo</label>
                                    </fieldset>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('phone') is-invalid @enderror"
                                               id="phone" type="tel" name="phone" value="{{ $prefill['phone'] }}"
                                               placeholder=" " required>
                                        <label class="tf-field-label" for="phone">Telemóvel</label>
                                    </fieldset>
                                </div>
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('email') is-invalid @enderror"
                                               id="email" type="email" name="email" value="{{ $prefill['email'] }}"
                                               placeholder=" " required>
                                        <label class="tf-field-label" for="email">Email</label>
                                    </fieldset>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('nif') is-invalid @enderror"
                                               id="nif" type="text" name="nif" value="{{ old('nif') }}"
                                               placeholder=" ">
                                        <label class="tf-field-label" for="nif">NIF (opcional)</label>
                                    </fieldset>
                                </div>
                            </div>
=======
    <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm" enctype="multipart/form-data">
        @csrf
        <div class="co-grid">
            <div>
                <div class="co-card">
                    <h2>Contacto</h2>
                    <div class="grid2">
                        <div class="field">
                            <label>Nome completo</label>
                            <input type="text" name="name" value="{{ $prefill['name'] }}" class="@error('name') is-invalid @enderror" required>
                            @error('name') <span class="err">{{ $message }}</span> @enderror
>>>>>>> Stashed changes
                        </div>

                        <div class="box-ip-checkout">
                            <h6 class="title">Morada de entrega</h6>
                            <div class="form-content gap-15">
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input @error('address') is-invalid @enderror"
                                           id="address" type="text" name="address" value="{{ old('address') }}"
                                           placeholder=" " required>
                                    <label class="tf-field-label" for="address">Morada</label>
                                </fieldset>
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('postal_code') is-invalid @enderror"
                                               id="postal_code" type="text" name="postal_code"
                                               value="{{ old('postal_code') }}" placeholder=" ">
                                        <label class="tf-field-label" for="postal_code">Código postal (opcional)</label>
                                    </fieldset>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('city') is-invalid @enderror"
                                               id="city" type="text" name="city" value="{{ old('city') }}"
                                               placeholder=" " required>
                                        <label class="tf-field-label" for="city">Localidade</label>
                                    </fieldset>
                                </div>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input @error('notes') is-invalid @enderror"
                                           id="notes" type="text" name="notes" value="{{ old('notes') }}"
                                           placeholder=" ">
                                    <label class="tf-field-label" for="notes">Notas (opcional)</label>
                                </fieldset>
                                <div class="checkbox-wrap gap-8">
                                    <input type="checkbox" class="tf-check" id="billingDiff" name="billing_different"
                                           value="1" {{ old('billing_different') ? 'checked' : '' }}>
                                    <label for="billingDiff" class="text-caption cl-text-4">
                                        A morada de faturação é diferente da morada de entrega
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="box-ip-checkout" id="billingCard" @if (! old('billing_different')) hidden @endif>
                            <h6 class="title">Morada de faturação</h6>
                            <div class="form-content gap-15">
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input" id="billing_name" type="text"
                                           name="billing_name" value="{{ old('billing_name') }}" placeholder=" ">
                                    <label class="tf-field-label" for="billing_name">Nome / Empresa</label>
                                </fieldset>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input" id="billing_address" type="text"
                                           name="billing_address" value="{{ old('billing_address') }}" placeholder=" ">
                                    <label class="tf-field-label" for="billing_address">Morada</label>
                                </fieldset>
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input" id="billing_postal_code" type="text"
                                               name="billing_postal_code" value="{{ old('billing_postal_code') }}" placeholder=" ">
                                        <label class="tf-field-label" for="billing_postal_code">Código postal</label>
                                    </fieldset>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input" id="billing_city" type="text"
                                               name="billing_city" value="{{ old('billing_city') }}" placeholder=" ">
                                        <label class="tf-field-label" for="billing_city">Localidade</label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <div class="box-ip-shipping">
                            <h6 class="title">Método de envio</h6>
                            <fieldset class="mb-15">
                                <label for="ship-entrega" class="check-ship">
                                    <input type="radio" id="ship-entrega" class="tf-check-rounded style-small"
                                           name="shipping_display" value="ENTREGA" checked>
                                    <span class="text text-caption">
                                        <span>Entrega ao domicílio — 1 a 3 dias úteis</span>
                                        <span class="price">Grátis</span>
                                    </span>
                                </label>
                            </fieldset>
                            <fieldset>
                                <label for="ship-loja" class="check-ship">
                                    <input type="radio" id="ship-loja" class="tf-check-rounded style-small"
                                           name="shipping_display" value="LEVANTAMENTO">
                                    <span class="text text-caption">
                                        <span>Levantamento em loja — pronto em 24–72h</span>
                                        <span class="price">Grátis</span>
                                    </span>
                                </label>
                            </fieldset>
                        </div>

                        <div class="box-ip-payment">
                            <div class="title">
                                <h6 class="mb-4">Pagamento</h6>
                                <p class="text-caption cl-text-4">Todas as transações são seguras.</p>
                            </div>

                            <div class="payment-method-box" id="payment-method-box">
                                <div class="payment-item mb-15">
                                    <label for="pay-virement" class="payment-header">
                                        <input type="radio" name="payment_method" value="VIREMENT"
                                               class="tf-check-rounded style-small" id="pay-virement"
                                               {{ old('payment_method', 'VIREMENT') === 'VIREMENT' ? 'checked' : '' }} required>
                                        <span class="pay-title text-caption">
                                            {{ $paymentOptions['VIREMENT']['label'] }}
                                        </span>
                                    </label>
                                    <div class="payment-body mt-12">
                                        <p class="text-caption cl-text-4 mb-16">
                                            {{ $paymentOptions['VIREMENT']['desc'] }}.
                                            Utilize a referência da encomenda no descritivo da transferência.
                                        </p>

                                        <div class="dfp-co-bank">
                                            <div class="dfp-co-bank-title">Dados para transferência</div>
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
                                                <p class="text-caption cl-text-4 mt-10">
                                                    Faça a transferência e anexe o comprovativo abaixo.
                                                </p>
                                            @else
                                                <p class="text-caption cl-text-4">
                                                    Os dados bancários serão comunicados após a encomenda. Anexe o comprovativo assim que concluir a transferência.
                                                </p>
                                            @endif
                                        </div>

                                        <div class="dfp-co-proof mt-16 @error('transfer_proof') is-invalid @enderror">
                                            <div class="dfp-co-proof__head">
                                                <span class="dfp-co-proof__badge">Obrigatório</span>
                                                <h6 class="dfp-co-proof__title">Comprovativo de transferência</h6>
                                                <p class="dfp-co-proof__desc text-caption cl-text-4">
                                                    Envie a foto ou o PDF do recibo. O ficheiro segue automaticamente no email da encomenda.
                                                </p>
                                            </div>

                                            <label class="dfp-co-proof__drop" for="transfer_proof" id="transferProofDrop">
                                                <input type="file" name="transfer_proof" id="transfer_proof"
                                                       accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf" required
                                                       class="dfp-co-proof__input @error('transfer_proof') is-invalid @enderror">
                                                <span class="dfp-co-proof__icon" aria-hidden="true">
                                                    <i class="icon icon-Upload"></i>
                                                </span>
                                                <span class="dfp-co-proof__cta">
                                                    <strong>Clique para escolher o ficheiro</strong>
                                                    <span>ou arraste-o para aqui</span>
                                                </span>
                                                <span class="dfp-co-proof__hint">JPG, PNG, WEBP ou PDF — máx. 5 MB</span>
                                            </label>

                                            <div class="dfp-co-proof__preview" id="transferProofPreview" hidden>
                                                <div class="dfp-co-proof__preview-media" id="transferProofThumb" hidden></div>
                                                <div class="dfp-co-proof__preview-meta">
                                                    <p class="dfp-co-proof__file" id="transferProofName"></p>
                                                    <button type="button" class="dfp-co-proof__clear link-underline" id="transferProofClear">
                                                        Remover ficheiro
                                                    </button>
                                                </div>
                                            </div>

                                            @error('transfer_proof')
                                                <p class="dfp-co-proof__error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checkbox-wrap gap-8 mt-16">
                                <input type="checkbox" class="tf-check" id="terms" name="terms" value="1"
                                       {{ old('terms') ? 'checked' : '' }} required>
                                <label for="terms" class="text-caption cl-text-4">
                                    Li e aceito os
                                    <a href="/ajuda/termos-e-condicoes" target="_blank" class="link-underline">termos e condições</a>
                                    e a
                                    <a href="/ajuda/politica-privacidade" target="_blank" class="link-underline">política de privacidade</a>.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="tf-page-cart-sidebar sticky-top">
                        <div class="cart-box order-box">
                            <h6 class="title cl-text-main">No seu carrinho</h6>
                            <ul class="list-order-product">
                                @foreach ($cart['items'] as $it)
                                    <li class="order-item">
                                        <figure class="img-product">
                                            <img src="/images/120-120/{{ $it['images'][0] ?? 'Desconto.jpg' }}"
                                                 alt="{{ $it['nome'] }}"
                                                 onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
                                            <span class="quantity">{{ $it['qtd'] }}</span>
                                        </figure>
                                        <div class="content">
                                            <div class="info">
                                                <p class="name text-caption fw-medium cl-text-main">{{ $it['nome'] }}</p>
                                                @if (! empty($it['variation']))
                                                    <span class="variant text-extra-small fw-medium text-black_60">
                                                        @foreach ((array) $it['variation'] as $v)
                                                            {{ is_array($v) ? implode(': ', [array_key_first($v), reset($v)]) : $v }}@if (! $loop->last), @endif
                                                        @endforeach
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="price text-caption fw-medium">{{ $fmt($it['total']) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="list-total cl-text-main">
                                <li class="total-item text-caption d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span class="price-sub fw-medium">{{ $fmt($cart['subtotal']) }}</span>
                                </li>
                                <li class="total-item text-caption d-flex justify-content-between">
                                    <span>Envio:</span>
                                    <span class="price-ship fw-medium">Grátis</span>
                                </li>
                            </ul>
                            <div class="subtotal h6 d-flex justify-content-between cl-text-main">
                                <span>Total:</span>
                                <span class="total-price-order">{{ $fmt($cart['subtotal']) }}</span>
                            </div>
                            <p class="text-caption cl-text-4 mt-10 mb-16">
                                Envio gratuito para todo o Portugal.<br>
                                Entrega em 1 a 3 dias úteis.
                            </p>
                            <div class="btn-order">
                                <button type="submit" class="tf-btn animate-btn size3 rounded-6 w-100">
                                    Confirmar encomenda
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="flat-spacing-2 bg-main">
    <div class="container">
        <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2"
             data-mobile="1" data-space="15" data-pagination="1">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Package"></i></span>
                        <div class="content">
                            <p class="title h5">Envio gratuito</p>
                            <p class="text text-caption">Em todo o Portugal continental</p>
                        </div>
                    </div>
<<<<<<< Updated upstream
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Return"></i></span>
                        <div class="content">
                            <p class="title h5">Devoluções</p>
                            <p class="text text-caption">Prazo legal de 14 dias</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Chating"></i></span>
                        <div class="content">
                            <p class="title h5">Apoio ao cliente</p>
                            <p class="text text-caption">Estamos disponíveis para ajudar</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Credit"></i></span>
                        <div class="content">
                            <p class="title h5">Pagamento seguro</p>
                            <p class="text text-caption">Transferência bancária verificada</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
=======
                @endforeach

                <div class="row"><span>Subtotal</span><span>{{ number_format((float) $cart['subtotal'], 2, ',', ' ') }}€</span></div>
                <div class="row"><span>Portes de envio</span><span>Grátis</span></div>
                <div class="row total"><span>Total</span><span>{{ number_format((float) $cart['subtotal'], 2, ',', ' ') }}€</span></div>
                <p style="font-size:13px;color:#555;margin:8px 0 14px;">
                    Envio gratuito para todo o Portugal.<br>
                    Entrega em 1 a 3 dias úteis (preparação: 1 dia útil).
                </p>

                <div class="co-pay">
                    <h2>Pagamento</h2>
                    <label class="opt sel" id="payOptVirement">
                        <input type="radio" name="payment_method" value="VIREMENT"
                               {{ old('payment_method', 'VIREMENT') === 'VIREMENT' ? 'checked' : '' }} required>
                        <div>
                            <div class="t">{{ $paymentOptions['VIREMENT']['label'] }}</div>
                            <div class="d">{{ $paymentOptions['VIREMENT']['desc'] }}</div>
                        </div>
                    </label>
                    @error('payment_method') <span class="err">{{ $message }}</span> @enderror

                    <div class="co-bank">
                        <div class="co-bank-title">Dados para transferência</div>
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
                            <p class="co-bank-hint">Faça a transferência e anexe o comprovativo abaixo. O ficheiro será enviado por email com a encomenda.</p>
                        @else
                            <p class="co-bank-hint">Os dados bancários serão comunicados após a encomenda. Anexe o comprovativo assim que concluir a transferência.</p>
                        @endif
                    </div>

                    <div class="co-proof">
                        <label class="co-proof-label" for="transfer_proof">Comprovativo de transferência</label>
                        <input type="file" name="transfer_proof" id="transfer_proof" accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf" required
                               class="@error('transfer_proof') is-invalid @enderror">
                        <p class="co-proof-hint">JPG, PNG, WEBP ou PDF — máx. 5 MB</p>
                        <p class="co-proof-name" id="transferProofName" hidden></p>
                        @error('transfer_proof') <span class="err">{{ $message }}</span> @enderror
                    </div>
                </div>

                <label class="check-row">
                    <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                    <span>Li e aceito os <a href="/ajuda/termos-e-condicoes" target="_blank">termos e condições</a>.</span>
                </label>
                @error('terms') <span class="err">{{ $message }}</span> @enderror

                <button type="submit" class="co-submit">Confirmar encomenda</button>
            </aside>
>>>>>>> Stashed changes
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
<<<<<<< Updated upstream
    var drop = document.getElementById('transferProofDrop');
    var preview = document.getElementById('transferProofPreview');
    var proofName = document.getElementById('transferProofName');
    var thumb = document.getElementById('transferProofThumb');
    var clearBtn = document.getElementById('transferProofClear');
    var objectUrl = null;

    function resetProofPreview() {
        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }
        if (preview) preview.hidden = true;
        if (thumb) {
            thumb.hidden = true;
            thumb.innerHTML = '';
        }
        if (proofName) proofName.textContent = '';
        if (drop) drop.classList.remove('has-file');
    }

    function showProof(file) {
        if (!file || !preview || !proofName) return;
        proofName.textContent = file.name + ' (' + Math.max(1, Math.round(file.size / 1024)) + ' KB)';
        preview.hidden = false;
        if (drop) drop.classList.add('has-file');
        if (thumb) {
            thumb.hidden = true;
            thumb.innerHTML = '';
            if (file.type && file.type.indexOf('image/') === 0) {
                objectUrl = URL.createObjectURL(file);
                var img = document.createElement('img');
                img.src = objectUrl;
                img.alt = 'Pré-visualização do comprovativo';
                thumb.appendChild(img);
                thumb.hidden = false;
            } else {
                thumb.innerHTML = '<span class="dfp-co-proof__pdf">PDF</span>';
                thumb.hidden = false;
            }
        }
    }

    if (proof) {
        proof.addEventListener('change', function () {
            resetProofPreview();
            var file = proof.files && proof.files[0];
            if (file) showProof(file);
        });
    }

    if (drop && proof) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            drop.addEventListener(evt, function (e) {
                e.preventDefault();
                drop.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            drop.addEventListener(evt, function (e) {
                e.preventDefault();
                drop.classList.remove('is-dragover');
            });
        });
        drop.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (!files || !files.length) return;
            try {
                var dt = new DataTransfer();
                dt.items.add(files[0]);
                proof.files = dt.files;
            } catch (err) {
                return;
            }
            proof.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    if (clearBtn && proof) {
        clearBtn.addEventListener('click', function () {
            proof.value = '';
            resetProofPreview();
=======
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
>>>>>>> Stashed changes
        });
    }
})();
</script>
@endpush
