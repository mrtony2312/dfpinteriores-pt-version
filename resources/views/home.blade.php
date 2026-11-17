@extends('layouts.omniva')

@section('title', 'DFP Interiores — Sofás e Mobiliário para a sua casa')

@section('content')
{{-- Carrousel promo juste après le header --}}
@include('partials.home-promo-slider')

{{-- Categories avec défilement auto --}}
<div class="flat-spacing">
    <div class="container">
        <div class="sect-heading center spacing-bottom-2 wow fadeInUpBlur">
            <h3 class="s-title mb-24">Comprar por categoria</h3>
            <p class="s-desc">Encontre o estilo certo para cada divisão da sua casa.</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper" data-preview="6" data-tablet="4" data-mobile-sm="3"
             data-mobile="2" data-space="16" data-pagination="2" data-pagination-sm="3" data-pagination-md="4"
             data-pagination-lg="6" data-auto="true" data-loop="true" data-delay="3200" data-speed="800"
             data-pause-hover="true">
            <div class="swiper-wrapper">
                @foreach ($shopCategories as $i => $cat)
                    <div class="swiper-slide">
                        <a href="/{{ $cat['slug'] }}" class="category-v02 style-2 hover-img wow fadeInUpBlur">
                            <div class="cate-image img-style">
                                <img loading="lazy" width="180" height="180"
                                     src="{{ $cat['image'] }}"
                                     alt="{{ $cat['name'] }}"
                                     onerror="this.onerror=null;this.src='/omniva/images/category/pet/cate-{{ ($i % 5) + 1 }}.jpg'">
                            </div>
                            <div class="cate-content">
                                <p class="cate-name cl-text-main h6 link-underline">{{ $cat['name'] }}</p>
                                <p class="cate-count text-caption letter-space--1">{{ $cat['count'] }} produtos</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="sw-line-default tf-sw-pagination wow fadeIn"></div>
        </div>
    </div>
</div>

{{-- Grid collection --}}
<div class="themesFlat">
    <div class="container">
        <a href="/mobiliario" class="collection-v09 style-v4 hover-img dfp-promo-banner wow fadeInUpBlur d-block">
            <div class="cls-image img-style">
                <picture class="dfp-promo-banner__media">
                    <source type="image/avif" srcset="/media/banners_sec/btn_mobiliario_exclusivo.avif">
                    <source type="image/webp" srcset="/media/banners_sec/btn_mobiliario_exclusivo.webp">
                    <img loading="lazy" decoding="async" width="1920" height="621"
                         src="/media/banners_sec/btn_mobiliario_exclusivo.jpg"
                         alt="Mobiliário exclusivo">
                </picture>
            </div>
        </a>
    </div>
</div>

{{-- Deal products --}}
@if ($dealProducts->isNotEmpty())
<div class="flat-spacing">
    <div class="container">
        <div class="sect-heading center spacing-bottom-2 wow fadeInUpBlur">
            <h3 class="s-title mb-24">Ofertas da semana</h3>
            <p class="s-desc">Seleção especial com envio rápido.</p>
        </div>
        <div class="tf-grid-layout tf-col-2 md-col-3 xl-col-4 gap-15">
            @foreach ($dealProducts as $product)
                <x-omniva.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Promo Conforto / Descanso --}}
<div class="themesFlat">
    <div class="container">
        <a href="/mobiliario" class="dfp-promo-banner dfp-promo-banner--wide wow fadeInUpBlur d-block">
            <div class="cls-image">
                <picture class="dfp-promo-banner__media">
                    <source type="image/avif" srcset="/media/banners/emma.avif">
                    <source type="image/webp" srcset="/media/banners/emma.webp">
                    <img loading="lazy" decoding="async" width="1200" height="400"
                         src="/media/banners/emma.jpg" alt="Conforto para todos os dias">
                </picture>
            </div>
            <span class="dfp-promo-banner__cta">
                Ver os móveis <i class="icon icon-ArrowRightUp fs-12"></i>
            </span>
        </a>
    </div>
</div>

{{-- Camas en offre, grille 2 x 2 --}}
@if ($bedOffers->isNotEmpty())
<div class="flat-spacing">
    <div class="container">
        <div class="sect-heading center spacing-bottom-2 wow fadeInUpBlur">
            <h3 class="s-title mb-24">Ofertas em camas</h3>
            <p class="s-desc">Quatro modelos em promoção, prontos a entregar.</p>
            <a href="/camas" class="tf-btn-line text-caption mt-16">Ver todas as camas</a>
        </div>
        <div class="tf-grid-layout tf-col-2 gap-15">
            @foreach ($bedOffers as $product)
                <x-omniva.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Colchões --}}
@if ($mattresses->isNotEmpty())
<div class="flat-spacing pt-0">
    <div class="container">
        <div class="sect-heading center spacing-bottom-2 wow fadeInUpBlur">
            <h3 class="s-title mb-24" id="colchoes-home">Colchões</h3>
            <p class="s-desc">Uma seleção para noites mais confortáveis.</p>
            <a href="/colchoes" class="tf-btn-line text-caption mt-16">Ver todos os colchões</a>
        </div>
        <div class="tf-grid-layout tf-col-2 gap-15">
            @foreach ($mattresses as $product)
                <x-omniva.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Testimonials --}}
<div class="flat-spacing">
    <div class="container">
        <div class="sect-heading center spacing-bottom-2 wow fadeInUpBlur">
            <div class="tf-list gap-10 align-items-center mb-10 justify-content-center">
                <div class="star-wrap fs-14 style-yellow">
                    <i class="icon icon-Star"></i><i class="icon icon-Star"></i><i class="icon icon-Star"></i>
                    <i class="icon icon-Star"></i><i class="icon icon-Star"></i>
                </div>
                <span class="text-bg-22 fw-medium text-caption lh-xl-28">Clientes satisfeitos em todo o país</span>
            </div>
            <h3 class="s-title mb-24">Opiniões dos clientes</h3>
            <p class="s-desc">Qualidade, entrega rápida e apoio próximo.</p>
        </div>
        <div dir="ltr" class="swiper tf-swiper" data-preview="3" data-tablet="3" data-mobile-sm="2"
             data-mobile="1" data-space="14" data-pagination="1" data-pagination-sm="2" data-pagination-md="3"
             data-pagination-lg="3">
            <div class="swiper-wrapper">
                @foreach ([
                    ['name' => 'Ana Sousa', 'text' => 'Sofá excelente, muito confortável. Entrega rápida e equipa simpática.'],
                    ['name' => 'Miguel Costa', 'text' => 'Boa qualidade/preço. O processo de compra foi simples e transparente.'],
                    ['name' => 'Rita Mendes', 'text' => 'Mobiliário impecável. Recomendo a DFP Interiores sem hesitação.'],
                    ['name' => 'João Alves', 'text' => 'Comprei um pack de quarto e ficou perfeito. Apoio ao cliente top.'],
                ] as $rev)
                    <div class="swiper-slide">
                        <div class="testimonial-v05 wow fadeInUpBlur">
                            <div class="star-wrap style-yellow">
                                <i class="icon icon-Star2 fs-14"></i><i class="icon icon-Star2 fs-14"></i>
                                <i class="icon icon-Star2 fs-14"></i><i class="icon icon-Star2 fs-14"></i>
                                <i class="icon icon-Star2 fs-14"></i>
                            </div>
                            <div class="tes-author">
                                <a href="#" class="name cl-text-main fw-medium link-underline">{{ $rev['name'] }}</a>
                                <div class="check-verified">
                                    <img loading="lazy" width="16" height="16" src="/omniva/icon/png/verified.png" alt="">
                                    <span class="text-caption fw-medium">Verificado</span>
                                </div>
                            </div>
                            <p class="tes-text">"{{ $rev['text'] }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="sw-dot-default tf-sw-pagination wow fadeInUp"></div>
        </div>
    </div>
</div>

{{-- Box icons --}}
<div class="flat-spacing-2 position-relative">
    <span class="br-line fake-class top-0"></span>
    <div class="container-2">
        <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2"
             data-mobile="1" data-space="15" data-pagination="1" data-pagination-sm="2" data-pagination-md="3"
             data-pagination-lg="4">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInUp" data-wow-delay="0s">
                        <span class="icon"><i class="icon-Package"></i></span>
                        <div class="content">
                            <p class="title h5">Entrega gratuita</p>
                            <p class="text text-caption cl-text-main">Em todo o Portugal, 1 a 3 dias úteis</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInUp" data-wow-delay="0.1s">
                        <span class="icon"><i class="icon-Return"></i></span>
                        <div class="content">
                            <p class="title h5">Devoluções fáceis</p>
                            <p class="text text-caption cl-text-main">14 dias para devolver com tranquilidade</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInUp" data-wow-delay="0.2s">
                        <span class="icon"><i class="icon-Chating"></i></span>
                        <div class="content">
                            <p class="title h5">Apoio ao cliente</p>
                            <p class="text text-caption cl-text-main">Telefone, email ou WhatsApp</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="box-icon_V01 wow fadeInUp" data-wow-delay="0.3s">
                        <span class="icon"><i class="icon-Credit"></i></span>
                        <div class="content">
                            <p class="title h5">Pagamento seguro</p>
                            <p class="text text-caption cl-text-main">Multibanco e MB WAY</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-line-default tf-sw-pagination wow fadeInUp"></div>
        </div>
    </div>
</div>
@endsection
