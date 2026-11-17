@extends('layouts.omniva')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('styles')
<link rel="stylesheet" href="/omniva/css/photoswipe.css">
<link rel="stylesheet" href="/assets/css/product-omniva.css?v=2">
<script type="application/ld+json">@json($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>
@endpush

@section('content')
@php
    $price = (float) $price;
    $priceBefore = (float) $priceBefore;
    $hasDiscount = $priceBefore > $price && $price > 0;
    $discountPct = $hasDiscount ? (int) round((($priceBefore - $price) / $priceBefore) * 100) : 0;
    $fmt = fn (float $v) => number_format($v, 2, ',', ' ').'€';
    $firstImage = $gallery[0]['url'] ?? asset('assets/images/no-photo.svg');
    $shortPlain = trim(strip_tags((string) $shortDesc));
@endphp

{{-- Breadcrumb --}}
<div class="tf-page-title single">
    <div class="container">
        <div class="content">
            <ul class="breadcrumb text-extra-small fw-medium">
                @foreach ($breadcrumbs as $i => $crumb)
                    @if ($i > 0)
                        <li class="br-dot"></li>
                    @endif
                    <li @if ($crumb['url'] === null) class="cl-text-main" @endif>
                        @if ($crumb['url'])
                            <a href="{{ $crumb['url'] }}" class="link-black link-underline">{{ $crumb['label'] }}</a>
                        @else
                            {{ $crumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Product Single (Omniva product-no-zoom) --}}
<section class="section-product-single tf-main-product" id="dfpProduct" data-product-token="{{ $productToken }}">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="tf-product-media-wrap sticky-top">
                    <div class="product-thumbs-slider style-row row_left">
                        <div class="flat-wrap-media-product">
                            <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started" data-spacing="0">
                                <div class="swiper-wrapper">
                                    @foreach ($gallery as $img)
                                        <div class="swiper-slide">
                                            <a href="{{ $img['url'] }}" target="_blank" class="item"
                                               data-pswp-width="1076px" data-pswp-height="1340px">
                                                <img loading="lazy" width="538" height="670"
                                                     src="{{ $img['url'] }}"
                                                     alt="{{ $productName }}"
                                                     onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if (count($gallery) > 1)
                            <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom"
                                 data-direction="vertical" data-preview="5">
                                <div class="swiper-wrapper stagger-wrap">
                                    @foreach ($gallery as $img)
                                        <div class="swiper-slide stagger-item">
                                            <div class="item">
                                                <img loading="lazy" width="105" height="130"
                                                     src="{{ $img['url'] }}" alt="{{ $productName }}"
                                                     onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="tf-product-info-wrap position-relative mt-md-0" id="tfProductInfoWrap">
                    <div class="tf-product-info-list other-image-zoom">
                        <div class="tf-product-info-heading">
                            @if ($productCategory)
                                <p class="product-infor-badge text-caption cl-text-main fw-medium">
                                    {{ $productCategory }}
                                </p>
                            @endif
                            <h1 class="product-infor-name h3">{{ $productName }}</h1>
                            @if ($productRef)
                                <p class="text-caption cl-text-7 mb-8">Ref: {{ $productRef }}</p>
                            @endif

                            <div class="product-infor-price" data-dfp-price="1">
                                <span class="h4 text-primary dfp-price-sale" id="dfpPriceSale">{{ $fmt($price) }}</span>
                                <span class="cl-text-main fw-medium text-decoration-line-through dfp-price-old {{ $hasDiscount ? '' : 'd-none' }}" id="dfpPriceOld">
                                    {{ $hasDiscount ? $fmt($priceBefore) : '' }}
                                </span>
                                <span class="badge-sale text-extra-small fw-medium style-fill {{ $hasDiscount ? '' : 'd-none' }}" id="dfpPriceBadge">
                                    -{{ $discountPct }}%
                                </span>
                            </div>

                            <div class="tf-product-shipping cl-text-main">
                                <a href="/ajuda/politica-de-envios" class="text-decoration-underline link">Envio</a>
                                gratuito em todo o Portugal.
                            </div>

                            <div class="tf-product-progress-sale">
                                <div class="title text-body-s fw-normal">
                                    <span class="ic-wrap tf-ani-flash">
                                        <i class="icon icon-Thunder" style="color:#F2721C;"></i>
                                    </span>
                                    <p class="cl-text-main" id="dfpStockLabel">
                                        @if ($inStock)
                                            Disponível para encomenda — entrega rápida.
                                        @else
                                            Sob consulta / por encomenda.
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if ($shortPlain !== '')
                                <div class="tf-product-description cl-text-7 letter-space--2">
                                    {{ Str::limit($shortPlain, 280) }}
                                </div>
                            @endif
                        </div>

                        <div class="tf-product-variant">
                            @if (count($colorVariants) > 0)
                                <div class="variant-picker-item variant-color">
                                    <div class="variant-picker-label">
                                        <div>
                                            <span class="fw-medium">Cor:</span>
                                            <span class="variant-picker-label-value value-currentColor text-capitalize" id="dfpColorLabel">
                                                {{ $colorVariants[0]['name'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="variant-picker-values" id="dfpColorValues">
                                        @foreach ($colorVariants as $i => $variant)
                                            <div class="hover-tooltip tooltip-bot color-btn {{ $i === 0 ? 'active' : '' }}"
                                                 role="button"
                                                 tabindex="0"
                                                 data-color="{{ Str::slug($variant['color']) }}"
                                                 data-pid="{{ $variant['pid'] }}"
                                                 data-name="{{ $variant['name'] }}"
                                                 data-price="{{ $variant['price'] }}"
                                                 data-price-before="{{ $variant['price_before'] }}"
                                                 data-stock="{{ $variant['stock'] }}"
                                                 data-image="{{ $variant['image'] }}">
                                                <span class="check-color" style="background:{{ $variant['colorcode'] }};@if(strtolower($variant['colorcode'])==='#ffffff' || strtolower($variant['colorcode'])==='#fff')border:1px solid #ddd;@endif"></span>
                                                <span class="tooltip">{{ $variant['name'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="tf-product-total-quantity">
                                <div class="group-action">
                                    <div class="wg-quantity">
                                        <button type="button" class="btn-quantity btn-decrease" aria-label="Diminuir">
                                            <i class="icon icon-Minus fs-10"></i>
                                        </button>
                                        <input class="quantity-product" id="dfpQty" type="text" name="number" value="1" inputmode="numeric">
                                        <button type="button" class="btn-quantity btn-increase" aria-label="Aumentar">
                                            <i class="icon icon-Plus fs-10"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="tf-btn style-2 style-stroke-2 size3 w-100" id="dfpAddCart">
                                        Adicionar ao carrinho
                                        <span class="dfp-add-price" id="dfpAddPrice">{{ $fmt($price) }}</span>
                                    </button>
                                </div>
                                <a href="{{ route('checkout') }}" class="tf-btn style-2 size3 animate-btn" id="dfpBuyNow">
                                    Comprar agora
                                </a>
                            </div>
                        </div>

                        <div class="tf-product-delivery">
                            <div class="product-delivery">
                                <i class="icon icon-Truck cl-text-main"></i>
                                <p class="text-caption">
                                    Entrega estimada:
                                    <span class="cl-text-main fw-medium">1 a 3 dias úteis</span>
                                </p>
                            </div>
                            <span class="br-line type-vertical"></span>
                            <div class="product-delivery">
                                <i class="icon icon-Box2 cl-text-main"></i>
                                <p class="text-caption">
                                    <span class="cl-text-main">Envio gratuito</span>
                                    em todo o Portugal
                                </p>
                            </div>
                        </div>

                        <div class="tf-product-pickup-available">
                            <i class="icon icon-CheckCircle"></i>
                            <div class="content">
                                <p class="text-caption">
                                    <span class="cl-text-main fw-medium">Levantamento disponível nas lojas DFP.</span>
                                    Preparação em 24–72h.
                                </p>
                                <a href="{{ route('lojas') }}" class="text-caption cl-text-main text-decoration-underline link">
                                    Ver lojas
                                </a>
                            </div>
                        </div>

                        <div class="tf-product-accordion" id="prdDes">
                            @if ($hasDesc)
                                <div class="accordion-item">
                                    <div class="accordion-action h6" data-bs-target="#faq-1" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="faq-1" role="button">
                                        <span>Descrição</span>
                                        <span class="icon ic-accordion-custom"></span>
                                    </div>
                                    <div id="faq-1" class="collapse show" data-bs-parent="#prdDes">
                                        <div class="accordion-content d-grid gap-14 text-caption">
                                            {!! $longDesc !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($hasSpecs)
                                <div class="accordion-item">
                                    <div class="accordion-action h6 {{ $hasDesc ? 'collapsed' : '' }}"
                                         data-bs-target="#faq-2" data-bs-toggle="collapse"
                                         aria-expanded="{{ $hasDesc ? 'false' : 'true' }}" aria-controls="faq-2" role="button">
                                        <span>Características</span>
                                        <span class="icon ic-accordion-custom"></span>
                                    </div>
                                    <div id="faq-2" class="collapse {{ $hasDesc ? '' : 'show' }}" data-bs-parent="#prdDes">
                                        <div class="accordion-content">
                                            <div class="dfp-specs">
                                                {!! $specsHtml !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="accordion-item">
                                <div class="accordion-action h6 collapsed" data-bs-target="#faq-3"
                                     data-bs-toggle="collapse" aria-expanded="false" aria-controls="faq-3" role="button">
                                    <span>Política de devoluções</span>
                                    <span class="icon ic-accordion-custom"></span>
                                </div>
                                <div id="faq-3" class="collapse" data-bs-parent="#prdDes">
                                    <div class="accordion-content d-grid gap-14">
                                        <p class="text-caption">
                                            Pode devolver o artigo no prazo de {{ \App\Domain\Merchant\MerchantPolicy::returnDays() }} dias após a entrega, em estado novo e completo.
                                        </p>
                                        <ul class="list text-caption">
                                            <li><span class="br-dot"></span> Devoluções aceites no prazo legal.</li>
                                            <li><span class="br-dot"></span> Artigo sem uso, com embalagem original.</li>
                                            <li>
                                                <span class="br-dot"></span>
                                                <a href="/ajuda/politica-de-devolucoes" class="link text-decoration-underline">Ver política completa</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Sticky ATC --}}
<div class="tf-sticky-btn-atc">
    <div class="container">
        <div class="tf-height-observer">
            <div class="tf-sticky-atc-product">
                <div class="atc-product-side">
                    <div class="prd_img">
                        <img loading="lazy" width="60" height="80" src="{{ $firstImage }}" alt="{{ $productName }}" id="dfpStickyImg">
                    </div>
                    <div class="prd_info d-none d-sm-grid">
                        <p class="name__prd fw-medium cl-text-main lh-24">{{ $productName }}</p>
                    </div>
                </div>
            </div>
            <div class="tf-sticky-atc-infos">
                <div class="tf-product-info-quantity">
                    <div class="wg-quantity py-2">
                        <button type="button" class="btn-quantity minus-btn" data-sticky-qty="-1" aria-label="Diminuir">
                            <i class="icon icon-Minus"></i>
                        </button>
                        <input class="quantity-product" id="dfpStickyQty" type="text" name="number" value="1" readonly>
                        <button type="button" class="btn-quantity plus-btn" data-sticky-qty="1" aria-label="Aumentar">
                            <i class="icon icon-Plus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="tf-btn size2 rounded-6 animate-btn btn-add-to-cart" id="dfpStickyAdd">
                    Adicionar ao carrinho
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Reviews --}}
<div class="section-customer-review flat-spacing">
    <div class="container">
        <div class="content">
            <div class="col-left box-rating sticky-md-top">
                <h3 class="title text-nowrap">Avaliações</h3>
                @php
                    $reviews = $product->reviews;
                    $avg = $reviews->count() ? round((float) $reviews->avg('rating'), 1) : 0;
                @endphp
                <div class="rating-ratio mb-24">
                    <div class="d-flex align-items-center gap-5">
                        <div class="star-wrap style-yellow">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="icon icon-Star2 {{ $i <= round($avg) ? '' : 'no-rate' }}"></i>
                            @endfor
                        </div>
                        <span class="fw-medium">({{ $reviews->count() }})</span>
                    </div>
                    <span class="fw-medium">{{ number_format($avg, 1) }}/5.0</span>
                </div>
            </div>
            <div class="col-right">
                <div class="box-comment">
                    @if ($reviews->isEmpty())
                        <p class="text-caption cl-text-7">Ainda não existem avaliações para este produto.</p>
                    @else
                        <ul class="comment-list">
                            @foreach ($reviews as $review)
                                <li class="comment-item">
                                    <div class="comment_content">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="author-infor">
                                                <span class="infor__name fw-medium cl-text-main">{{ $review->author }}</span>
                                                @if ($review->reviewed_at)
                                                    <span class="br-line type-vertical"></span>
                                                    <span class="info__date text-caption cl-text-main">
                                                        {{ optional($review->reviewed_at)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="star-wrap">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="icon icon-Star2 {{ $i <= (int) $review->rating ? '' : 'no-rate' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="comment_text text-caption">{{ $review->body }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Marquee --}}
<div class="infiniteSlide-text bg-main">
    <div class="infiniteSlide infiniteSlide-wrapper" data-clone="2" data-speed="100">
        <div class="infiniteSlide-item"><p class="text stroke-text_black h2 lh-xl-48">Envio gratuito</p></div>
        <div class="infiniteSlide-item"><span class="br-line bg-black"></span></div>
        <div class="infiniteSlide-item"><p class="text h2 lh-xl-48">Entrega 1 a 3 dias</p></div>
        <div class="infiniteSlide-item"><span class="br-line bg-black"></span></div>
        <div class="infiniteSlide-item"><p class="text stroke-text_black h2 lh-xl-48">Qualidade DFP</p></div>
        <div class="infiniteSlide-item"><span class="br-line bg-black"></span></div>
        <div class="infiniteSlide-item"><p class="text h2 lh-xl-48">Pagamento seguro</p></div>
        <div class="infiniteSlide-item"><span class="br-line bg-black"></span></div>
    </div>
</div>

{{-- Related --}}
@if ($relatedProducts->isNotEmpty())
<div class="themesFlat">
    <div class="flat-spacing pb-0">
        <div class="container">
            <h3 class="text-center spacing-bottom-2">Também pode gostar</h3>
            <div dir="ltr" class="swiper tf-swiper wrap-sw-over"
                 data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2"
                 data-space-xl="15" data-space="15" data-pagination="1">
                <div class="swiper-wrapper">
                    @foreach ($relatedProducts as $related)
                        <div class="swiper-slide">
                            <x-omniva.product-card :product="$related" />
                        </div>
                    @endforeach
                </div>
                <div class="sw-dot-default tf-sw-pagination"></div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="/omniva/js/zoom.js"></script>
<script>
(function () {
    // Masquer le preload si le thème ne l’a pas retiré
    const preload = document.getElementById('preload');
    if (preload) {
        preload.style.display = 'none';
        preload.remove();
    }

    const root = document.getElementById('dfpProduct');
    if (!root) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const productToken = root.dataset.productToken;
    let selectedPid = document.querySelector('#dfpColorValues .color-btn.active')?.dataset.pid || '';
    const qtyInput = document.getElementById('dfpQty');
    const stickyQty = document.getElementById('dfpStickyQty');

    function fmt(n) {
        const v = Number(n);
        if (!Number.isFinite(v)) return '—';
        return v.toFixed(2).replace('.', ',') + '€';
    }

    function syncQty(v) {
        v = Math.max(1, parseInt(v, 10) || 1);
        qtyInput.value = v;
        if (stickyQty) stickyQty.value = v;
        return v;
    }

    document.querySelectorAll('.btn-decrease, .minus-btn').forEach((btn) => {
        btn.addEventListener('click', () => syncQty((parseInt(qtyInput.value, 10) || 1) - 1));
    });
    document.querySelectorAll('.btn-increase, .plus-btn').forEach((btn) => {
        btn.addEventListener('click', () => syncQty((parseInt(qtyInput.value, 10) || 1) + 1));
    });
    qtyInput?.addEventListener('change', () => syncQty(qtyInput.value));

    function updatePrice(price, before) {
        const sale = document.getElementById('dfpPriceSale');
        const old = document.getElementById('dfpPriceOld');
        const badge = document.getElementById('dfpPriceBadge');
        const addPrice = document.getElementById('dfpAddPrice');
        price = Number(price) || 0;
        before = Number(before) || 0;
        if (sale) sale.textContent = fmt(price);
        if (addPrice) addPrice.textContent = fmt(price);
        const has = before > price && price > 0;
        if (old) {
            old.classList.toggle('d-none', !has);
            old.textContent = has ? fmt(before) : '';
        }
        if (badge) {
            badge.classList.toggle('d-none', !has);
            if (has) badge.textContent = '-' + Math.round(((before - price) / before) * 100) + '%';
        }
    }

    document.querySelectorAll('#dfpColorValues .color-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#dfpColorValues .color-btn').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            selectedPid = btn.dataset.pid || '';
            const label = document.getElementById('dfpColorLabel');
            if (label) label.textContent = btn.dataset.name || '';
            updatePrice(btn.dataset.price, btn.dataset.priceBefore);
            if (btn.dataset.image) {
                const sticky = document.getElementById('dfpStickyImg');
                if (sticky) sticky.src = btn.dataset.image;
            }
        });
    });

    async function addToCart(goCheckout) {
        const qtd = syncQty(qtyInput.value);
        const body = new URLSearchParams({
            product: productToken,
            qtd: String(qtd),
            _token: token || '',
        });
        if (selectedPid) body.set('variation', selectedPid);

        const btn = document.getElementById('dfpAddCart');
        const sticky = document.getElementById('dfpStickyAdd');
        const prev = btn?.textContent;
        try {
            if (btn) btn.textContent = 'A adicionar…';
            const r = await fetch('/carrinho/add', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body,
            });
            const data = await r.json();
            const badge = document.getElementById('cart-count-badge');
            if (badge && data.cart) {
                const n = (data.cart.items || []).reduce((sum, i) => sum + (i.qtd || 0), 0);
                badge.textContent = n > 0 ? String(n) : '';
                badge.classList.toggle('is-zero', n <= 0);
            }
            if (btn) btn.textContent = 'Adicionado';
            if (sticky) sticky.textContent = 'Adicionado';
            setTimeout(() => {
                if (btn) btn.textContent = prev || 'Adicionar ao carrinho';
                if (sticky) sticky.textContent = 'Adicionar ao carrinho';
            }, 1400);
            if (goCheckout) window.location.href = '{{ route('checkout') }}';
        } catch (e) {
            if (btn) btn.textContent = prev || 'Adicionar ao carrinho';
            alert('Não foi possível adicionar ao carrinho.');
        }
    }

    document.getElementById('dfpAddCart')?.addEventListener('click', () => addToCart(false));
    document.getElementById('dfpStickyAdd')?.addEventListener('click', () => addToCart(false));
    document.getElementById('dfpBuyNow')?.addEventListener('click', (e) => {
        e.preventDefault();
        addToCart(true);
    });

    // Init first variant price if present
    const first = document.querySelector('#dfpColorValues .color-btn.active');
    if (first) updatePrice(first.dataset.price, first.dataset.priceBefore);
})();
</script>
@endpush
