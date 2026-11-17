@extends('layouts.omniva')

@section('title', 'Finalizar encomenda | DFP Interiores')

@push('styles')
<link rel="stylesheet" href="/assets/css/checkout-omniva.css?v=8">
@endpush

@section('content')
@php
    $fmt = fn ($value) => number_format((float) $value, 2, ',', ' ').' €';
    $fullName = trim((string) old('name', $prefill['name'] ?? ''));
    $nameParts = preg_split('/\s+/', $fullName, 2) ?: [];
    $firstName = old('firstname', $nameParts[0] ?? '');
    $lastName = old('lastname', $nameParts[1] ?? '');
    $billingDifferent = (bool) old('billing_different');
    $shipMethod = old('shipping_method', 'ENTREGA');
    $districts = [
        'Aveiro', 'Beja', 'Braga', 'Bragança', 'Castelo Branco', 'Coimbra', 'Évora', 'Faro',
        'Guarda', 'Leiria', 'Lisboa', 'Portalegre', 'Porto', 'Santarém', 'Setúbal',
        'Viana do Castelo', 'Vila Real', 'Viseu', 'Açores', 'Madeira',
    ];
    $variantOf = function (array $item): string {
        $parts = [];
        foreach ((array) ($item['variation'] ?? []) as $pair) {
            if (is_array($pair) && $pair !== []) {
                $parts[] = (string) reset($pair);
            }
        }

        return implode(' / ', array_filter($parts));
    };
@endphp

<div class="tf-page-title-2 flat-spacing-2">
    <div class="container">
        <div class="content">
            <ul class="breadcrumb h5 fs-15">
                <li>
                    <a href="{{ route('home') }}" class="link-underline">INÍCIO</a>
                </li>
                <li>/</li>
                <li>CARRINHO</li>
            </ul>
            <h3 class="text-center lh_100">Finalizar encomenda</h3>
        </div>
    </div>
</div>

<div class="flat-spacing pt-0">
    <div class="container">
        <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm">
            @csrf
            <input type="hidden" name="name" id="fullName" value="{{ $fullName }}">
            <input type="hidden" name="billing_different" id="billingDifferent" value="{{ $billingDifferent ? '1' : '0' }}">

            <div class="row gy-30">
                <div class="col-lg-8">
                    <div class="tf-checkout-cart-main">
                        @if ($errors->any())
                            <div class="dfp-co-errors mb-16">
                                <strong>Não foi possível concluir. Corrija os campos assinalados:</strong>
                                <ul>
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="box-ip-checkout">
                            <h6 class="title">Checkout</h6>
                            <div class="form-content gap-15">
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <div class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('name') is-invalid @enderror" id="firstname" placeholder=" " type="text" name="firstname" value="{{ $firstName }}" required>
                                        <label class="tf-field-label" for="firstname">Nome</label>
                                    </div>
                                    <div class="tf-field style-2">
                                        <input class="tf-field-input tf-input" id="lastname" placeholder=" " type="text" name="lastname" value="{{ $lastName }}">
                                        <label class="tf-field-label" for="lastname">Apelido</label>
                                    </div>
                                </div>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input" id="country" type="text" value="Portugal" placeholder=" " readonly>
                                    <label class="tf-field-label" for="country">País</label>
                                </fieldset>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input @error('address') is-invalid @enderror" id="address" type="text" name="address" value="{{ old('address') }}" placeholder=" " required>
                                    <label class="tf-field-label" for="address">Morada</label>
                                    @error('address') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                </fieldset>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input" id="apartment" type="text" name="notes" value="{{ old('notes') }}" placeholder=" ">
                                    <label class="tf-field-label" for="apartment">Apartamento, andar, etc. (opcional)</label>
                                </fieldset>
                                <div class="tf-grid-layout gap-16 sm-col-3">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('city') is-invalid @enderror" id="city" type="text" name="city" value="{{ old('city') }}" placeholder=" " required>
                                        <label class="tf-field-label" for="city">Cidade</label>
                                        @error('city') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                    </fieldset>
                                    <div class="tf-select text-caption">
                                        <select name="district" id="state" class="h-100 rounded-4 @error('district') is-invalid @enderror">
                                            <option value="">Distrito</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district }}" @selected(old('district') === $district)>{{ $district }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('postal_code') is-invalid @enderror" id="code" type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder=" ">
                                        <label class="tf-field-label" for="code">Código postal</label>
                                        @error('postal_code') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                    </fieldset>
                                </div>
                                <div class="tf-grid-layout gap-16 sm-col-2">
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('phone') is-invalid @enderror" id="phone" type="text" name="phone" value="{{ old('phone', $prefill['phone'] ?? '') }}" placeholder=" " required>
                                        <label class="tf-field-label" for="phone">Telefone</label>
                                        @error('phone') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                    </fieldset>
                                    <fieldset class="tf-field style-2">
                                        <input class="tf-field-input tf-input @error('nif') is-invalid @enderror" id="nif" type="text" name="nif" value="{{ old('nif') }}" placeholder=" ">
                                        <label class="tf-field-label" for="nif">NIF (opcional)</label>
                                    </fieldset>
                                </div>
                                <fieldset class="tf-field style-2">
                                    <input class="tf-field-input tf-input @error('email') is-invalid @enderror" id="email" placeholder=" " type="email" name="email" value="{{ old('email', $prefill['email'] ?? '') }}" required>
                                    <label class="tf-field-label" for="email">Email</label>
                                    @error('email') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                                </fieldset>
                                @guest
                                    <a href="{{ route('cliente') }}" class="tf-btn-line text-caption">Entrar</a>
                                @endguest
                            </div>
                        </div>

                        <div class="box-ip-shipping">
                            <h6 class="title">Método de envio</h6>
                            <fieldset class="mb-15">
                                <label for="freeship" class="check-ship">
                                    <input type="radio" id="freeship" class="tf-check-rounded style-small" name="shipping_method" value="ENTREGA" @checked($shipMethod === 'ENTREGA')>
                                    <span class="text text-caption">
                                        <span>Entrega ao domicílio — 1 a 3 dias úteis, todo o Portugal</span>
                                        <span class="price">0,00 €</span>
                                    </span>
                                </label>
                            </fieldset>
                            <fieldset>
                                <label for="pickup" class="check-ship">
                                    <input type="radio" id="pickup" class="tf-check-rounded style-small" name="shipping_method" value="LEVANTAMENTO" @checked($shipMethod === 'LEVANTAMENTO')>
                                    <span class="text text-caption">
                                        <span>Levantamento em loja — pronto em 24 a 72h</span>
                                        <span class="price">0,00 €</span>
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
                                    <label for="bank-transfer" class="payment-header">
                                        <input type="radio" name="payment_method" value="VIREMENT" class="tf-check-rounded style-small" id="bank-transfer" checked>
                                        <span class="pay-title text-caption">Transferência bancária</span>
                                    </label>
                                    <div id="bank-transfer-payment" class="payment-body">
                                        <p class="text-caption cl-text-4 mb-0">
                                            Depois de validar a encomenda, mostramos o número de conta. A transferência deve ser feita de imediato.
                                        </p>
                                        <div class="checkbox-wrap gap-8 mt-16">
                                            <input type="checkbox" class="tf-check" id="checkShip" @checked(! $billingDifferent)>
                                            <label for="checkShip" class="text-caption cl-text-4">Usar a morada de envio como morada de faturação</label>
                                        </div>

                                        <div class="form-content gap-15 mt-16 dfp-billing" id="billingCard" @if(! $billingDifferent) hidden @endif>
                                            <fieldset class="tf-field style-2">
                                                <input class="tf-field-input tf-input @error('billing_name') is-invalid @enderror" id="billing_name" type="text" name="billing_name" value="{{ old('billing_name') }}" placeholder=" ">
                                                <label class="tf-field-label" for="billing_name">Nome / empresa de faturação</label>
                                            </fieldset>
                                            <fieldset class="tf-field style-2">
                                                <input class="tf-field-input tf-input @error('billing_address') is-invalid @enderror" id="billing_address" type="text" name="billing_address" value="{{ old('billing_address') }}" placeholder=" ">
                                                <label class="tf-field-label" for="billing_address">Morada de faturação</label>
                                            </fieldset>
                                            <div class="tf-grid-layout gap-16 sm-col-2">
                                                <fieldset class="tf-field style-2">
                                                    <input class="tf-field-input tf-input" id="billing_postal_code" type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" placeholder=" ">
                                                    <label class="tf-field-label" for="billing_postal_code">Código postal</label>
                                                </fieldset>
                                                <fieldset class="tf-field style-2">
                                                    <input class="tf-field-input tf-input @error('billing_city') is-invalid @enderror" id="billing_city" type="text" name="billing_city" value="{{ old('billing_city') }}" placeholder=" ">
                                                    <label class="tf-field-label" for="billing_city">Localidade</label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-caption cl-text-main">
                                Os seus dados são usados para processar a encomenda e para os fins descritos na
                                <a href="{{ route('ajuda.privacidade') }}" class="link-underline">política de privacidade</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="tf-page-cart-sidebar sticky-top">
                        <div class="cart-box order-box">
                            <h6 class="title cl-text-main">No seu carrinho</h6>
                            <ul class="list-order-product">
                                @foreach ($cart['items'] as $it)
                                    @php $variant = $variantOf($it); @endphp
                                    <li class="order-item">
                                        <figure class="img-product">
                                            <img src="{{ $it['image_url'] ?? '/assets/images/no-photo.svg' }}" alt="{{ $it['nome'] }}" width="72" height="94" onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
                                            <span class="quantity">{{ (int) $it['qtd'] }}</span>
                                        </figure>
                                        <div class="content">
                                            <div class="info">
                                                <p class="name text-caption fw-medium cl-text-main">{{ $it['nome'] }}</p>
                                                @if ($variant !== '')
                                                    <span class="variant text-extra-small fw-medium text-black_60">{{ $variant }}</span>
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
                                    <span>Portes:</span>
                                    <span class="price-ship fw-medium">Grátis</span>
                                </li>
                            </ul>
                            <div class="subtotal h6 d-flex justify-content-between cl-text-main">
                                <span>Total:</span>
                                <span class="total-price-order">{{ $fmt($cart['subtotal']) }}</span>
                            </div>
                            <div class="checkbox-wrap gap-8 mt-16">
                                <input type="checkbox" class="tf-check" id="terms" name="terms" value="1" @checked(old('terms')) required>
                                <label for="terms" class="text-caption cl-text-4">
                                    Li e aceito os <a href="{{ route('ajuda.termos') }}" class="link-underline" target="_blank">termos e condições</a>.
                                </label>
                            </div>
                            @error('terms') <span class="dfp-co-field-error">{{ $message }}</span> @enderror
                            <div class="btn-order">
                                <button type="submit" class="tf-btn animate-btn size3 rounded-6 w-100">Finalizar encomenda</button>
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
             data-mobile="1" data-space="15" data-pagination="1" data-pagination-sm="2" data-pagination-md="3"
             data-pagination-lg="3">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Package"></i></span>
                        <div class="content">
                            <p class="title h5">Entrega rápida</p>
                            <p class="text text-caption">Envio gratuito para todo o Portugal</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Return"></i></span>
                        <div class="content">
                            <p class="title h5">Devoluções em 14 dias</p>
                            <p class="text text-caption">Não ficou satisfeito? Devolução simples</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Chating"></i></span>
                        <div class="content">
                            <p class="title h5">Apoio ao cliente</p>
                            <p class="text text-caption">Estamos disponíveis quando precisar</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01">
                        <span class="icon"><i class="icon-Credit"></i></span>
                        <div class="content">
                            <p class="title h5">Pagamento seguro</p>
                            <p class="text text-caption">Transferência bancária com comprovativo</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dot-default tf-sw-pagination"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('checkoutForm');
    var first = document.getElementById('firstname');
    var last = document.getElementById('lastname');
    var full = document.getElementById('fullName');
    function syncName() {
        if (!full) return;
        var name = [first && first.value, last && last.value].map(function (v) {
            return (v || '').trim();
        }).filter(Boolean).join(' ');
        if (name) full.value = name;
    }
    if (first) first.addEventListener('input', syncName);
    if (last) last.addEventListener('input', syncName);
    if (form) form.addEventListener('submit', syncName);

    var same = document.getElementById('checkShip');
    var flag = document.getElementById('billingDifferent');
    var billing = document.getElementById('billingCard');
    function syncBilling() {
        var different = same && !same.checked;
        if (flag) flag.value = different ? '1' : '0';
        if (billing) {
            billing.hidden = !different;
            billing.querySelectorAll('input').forEach(function (input) {
                if (input.id === 'billing_name' || input.id === 'billing_address' || input.id === 'billing_city') {
                    input.required = different;
                }
            });
        }
    }
    if (same) same.addEventListener('change', syncBilling);
})();
</script>
@endpush
