<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DFP Interiores')</title>
    <meta name="description" content="@yield('meta_description', 'DFP Interiores: Especialistas em Sofás e Mobiliário para a sua casa. Qualidade, design moderno e os melhores preços.')">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    <meta name="googlebot" content="{{ $robots ?? 'index, follow' }}">
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="alternate" href="{{ $canonical }}" hreflang="pt-PT" />
    <link rel="alternate" href="{{ $canonical }}" hreflang="x-default" />
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png?v=3">

    <link rel="stylesheet" href="/omniva/fonts/fonts.css">
    <link rel="stylesheet" href="/omniva/icon/icomoon/style.css">
    <link rel="stylesheet" href="/omniva/css/bootstrap.min.css">
    <link rel="stylesheet" href="/omniva/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/omniva/css/animate.css">
    <link rel="stylesheet" href="/omniva/css/styles.css">
    <link rel="stylesheet" href="/assets/css/footer-classic.css?v=3">
    <link rel="stylesheet" href="/assets/css/shipping-marquee.css?v=2">
    <link rel="stylesheet" href="/assets/css/home-promo-slider.css?v=3">
    <link rel="stylesheet" href="/assets/css/site-nav.css?v=8">
    <style>
        /* Bannières promo — image entière, affichage normal */
        .dfp-promo-banner {
            position: relative;
            display: block;
            width: 100%;
            overflow: hidden;
            border-radius: 0;
            line-height: 0;
            text-decoration: none;
        }
        .dfp-promo-banner .cls-image,
        .dfp-promo-banner__media {
            display: block;
            width: 100%;
        }
        .dfp-promo-banner .cls-image img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: contain;
            object-position: center center;
        }
        .dfp-promo-banner--wide .cls-image img {
            width: 100%;
            height: auto;
        }
        .dfp-promo-banner__cta {
            position: absolute;
            left: 50%;
            bottom: 18px;
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            background: #fff;
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .02em;
            line-height: 1.2;
            border-radius: 6px;
            box-shadow: 0 6px 20px rgba(0,0,0,.18);
            white-space: nowrap;
            transition: background .2s ease, color .2s ease;
        }
        .dfp-promo-banner:hover .dfp-promo-banner__cta {
            background: #e5007d;
            color: #fff;
        }
        @media (max-width: 767px) {
            .dfp-promo-banner__cta {
                bottom: 12px;
                padding: 10px 16px;
                font-size: 13px;
            }
        }
        .mt-14 { margin-top: 14px; }
        #wrapper { overflow-x: clip; }
        .btn-dfp-pink { background: #e5007d !important; border-color: #e5007d !important; color: #fff !important; }
        .tf-btn.btn-purple, .tf-btn.btn-purple:hover { background: #e5007d; border-color: #e5007d; }
        .price-new.text-primary, .text-primary { color: #e5007d !important; }
        .card-product .product-action_bot .btn-action_choose { cursor: pointer; }

        /* Mobile header bar */
        @media (max-width: 1199px) {
            .tf-header .header-inner {
                padding-top: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            .tf-header .header-inner_wrap {
                display: grid !important;
                grid-template-columns: 1fr auto 1fr;
                align-items: center;
                min-height: 48px;
                gap: 8px;
            }
            .tf-header .header-left {
                justify-self: start;
                flex: unset !important;
            }
            .tf-header .header-center {
                justify-self: center;
                display: flex;
                justify-content: center;
                min-width: 0;
            }
            .tf-header .header-right {
                justify-self: end;
                flex: unset !important;
            }
            .tf-header .box-btn-open-menu a,
            .tf-header .nav-icon-item {
                width: 42px;
                height: 42px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                color: #1a1a1a;
                background: transparent;
                transition: background .2s ease;
            }
            .tf-header .box-btn-open-menu a:active,
            .tf-header .nav-icon-item:active,
            .tf-header .box-btn-open-menu a:hover,
            .tf-header .nav-icon-item:hover {
                background: #f3f3f3;
            }
            .tf-header .nav-icon-list {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 2px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .tf-header .nav-icon-list > li {
                display: flex;
                align-items: center;
            }
            .tf-header .nav-icon-item {
                position: relative;
            }
            .tf-header .nav-icon-item .icon,
            .tf-header .nav-icon-item i {
                font-size: 20px;
                line-height: 1;
            }
            .tf-header .nav-icon-item .number-order {
                position: absolute;
                top: 5px;
                right: 5px;
                min-width: 16px;
                height: 16px;
                padding: 0 4px;
                border-radius: 999px;
                background: #e5007d;
                color: #fff;
                font-size: 10px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }
            .tf-header .nav-icon-item .number-order:empty,
            .tf-header .nav-icon-item .number-order.is-zero {
                display: none;
            }
            .logo-text { font-size: 17px; }
        }
        @media (max-width: 575px) {
            .logo-text { font-size: 15px; }
            .tf-header .container-full {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }

        /* Mobile offcanvas menu */
        .canvas-mb .canvas-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
        }
        .canvas-mb .canvas-body {
            padding: 8px 18px 24px;
        }
        .canvas-mb .nav-ul-mb {
            list-style: none;
            margin: 0 0 20px;
            padding: 0;
        }
        .canvas-mb .nav-mb-item + .nav-mb-item {
            border-top: 1px solid #eee;
        }
        .canvas-mb .mb-menu-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
        }
        .canvas-mb .mb-menu-link:hover { color: #e5007d; }
        .canvas-mb .group-btn {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 18px;
        }
        .canvas-mb .need-help-wrap {
            padding-top: 8px;
            border-top: 1px solid #eee;
        }
        .popup-search .form-search {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        .popup-search .form-search input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
        }
        .popup-search .btn-action_submit {
            width: 48px;
            border: 0;
            border-radius: 10px;
            background: #e5007d;
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>
    <button id="goTop" type="button">
        <span class="border-progress"></span>
        <span class="ic-wrap"><span class="icon icon-ArrowCaretUp"></span></span>
    </button>

    <div class="preload preload-container" id="preload">
        <div class="preload-logo"><div class="spinner"></div></div>
    </div>

    <main id="wrapper">
        @include('partials.shipping-marquee')

        <div class="tf-topbar bg-dfp sm-d-none">
            <div class="container-full">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="tf-btn-swiper-main">
                            <div class="text-white fs-18 nav-prev-swiper"><i class="icon icon-ArrowCaretLeft"></i></div>
                            <div dir="ltr" class="swiper tf-swiper swiper-topbar" data-touch="false" data-auto="true" data-loop="true" data-speed="600" data-delay="4000">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="text-adver text-white text-caption">
                                            Descontos até 70% em sofás e mobiliário
                                            <span class="br-line bg-white"></span>
                                            <a href="/descontos70" class="fw-semibold link-underline">Ver agora</a>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="text-adver text-white text-caption">
                                            Entrega gratuita em todo o Portugal — 1 a 3 dias úteis
                                            <span class="br-line bg-white"></span>
                                            <a href="/ajuda/politica-de-envios" class="fw-semibold link-underline">Saber mais</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-white fs-18 nav-next-swiper"><i class="icon icon-ArrowCaretRight"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="tf-list justify-content-end">
                            <a href="/lojas" class="text-caption text-white link-underline">As nossas lojas</a>
                            <a href="/contactos" class="text-caption text-white link-underline">Contactos</a>
                            <a href="/apoioaocliente" class="text-caption text-white link-underline">Apoio ao cliente</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <header class="tf-header">
            <div class="header-inner">
                <div class="container-full">
                    <div class="header-inner_wrap">
                        <div class="header-left d-flex align-items-center">
                            <div class="box-btn-open-menu d-flex d-xl-none">
                                <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-label="Abrir menu">
                                    <i class="icon icon-OpenMenu fs-22"></i>
                                </a>
                            </div>
                            <a href="{{ route('home') }}" class="logo-site d-none d-xl-flex">
                                <span class="logo-text">DFP <span>Interiores</span></span>
                            </a>
                        </div>
                        <div class="header-center">
                            <a href="{{ route('home') }}" class="logo-site d-xl-none">
                                <span class="logo-text">DFP <span>Interiores</span></span>
                            </a>
                            <nav class="box-navigation d-none d-xl-block">
                                <ul class="box-nav-menu">
                                    <li class="menu-item">
                                        <a href="{{ route('home') }}" class="item-link"><span class="text">Início</span></a>
                                    </li>
                                    @foreach (($navCategories ?? collect()) as $cat)
                                        <li class="menu-item">
                                            <a href="/{{ $cat->slug }}" class="item-link">
                                                <span class="text">{{ $cat->displayName() }}</span>
                                                @if ($cat->children->isNotEmpty())
                                                    <i class="icon icon-ArrowCaretDown"></i>
                                                @endif
                                            </a>
                                            @if ($cat->children->isNotEmpty())
                                                <div class="sub-menu">
                                                    <ul class="sub-menu_list">
                                                        @foreach ($cat->children->take(10) as $child)
                                                            <li>
                                                                <a href="/{{ $child->slug }}" class="sub-menu_link text-caption">
                                                                    <span class="text">{{ $child->displayName() }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                    <li class="menu-item">
                                        <a href="/descontos70" class="item-link"><span class="text">Descontos</span></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="header-right">
                            <ul class="nav-icon-list tf-list justify-content-end">
                                <li>
                                    <a href="#search" data-bs-toggle="offcanvas" class="nav-icon-item link" aria-label="Procurar">
                                        <i class="icon icon-Search"></i>
                                    </a>
                                </li>
                                <li class="d-none d-sm-block">
                                    <a href="{{ route('cliente') }}" class="nav-icon-item link" aria-label="Conta">
                                        <i class="icon icon-User"></i>
                                    </a>
                                </li>
                                <li class="d-none d-sm-block">
                                    <a href="{{ route('wishlist') }}" class="nav-icon-item link has-number" aria-label="Favoritos">
                                        <i class="icon icon-Hearth2"></i>
                                        @if (($wishCount ?? 0) > 0)
                                            <span class="number-order">{{ $wishCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('carrinho') }}" class="nav-icon-item link has-number" aria-label="Carrinho">
                                        <i class="icon icon-ShoppingBag"></i>
                                        <span class="number-order {{ ($cartCount ?? 0) > 0 ? '' : 'is-zero' }}" id="cart-count-badge">{{ ($cartCount ?? 0) > 0 ? $cartCount : '' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')

        @include('partials.footer-classic')
    </main>

    <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
        <div class="canvas-header">
            <a href="{{ route('home') }}" class="logo-text">DFP <span>Interiores</span></a>
            <button type="button" class="link-rotate" data-bs-dismiss="offcanvas" aria-label="Fechar menu">
                <i class="icon icon-Close2"></i>
            </button>
        </div>
        <div class="br-line bg-line-5"></div>
        <div class="canvas-body">
            <div class="mb-content-top">
                <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                    <li class="nav-mb-item"><a href="{{ route('home') }}" class="mb-menu-link">Início</a></li>
                    @foreach (($navCategories ?? collect()) as $cat)
                        <li class="nav-mb-item">
                            <a href="/{{ $cat->slug }}" class="mb-menu-link">{{ $cat->displayName() }}</a>
                        </li>
                    @endforeach
                    <li class="nav-mb-item"><a href="/descontos70" class="mb-menu-link">Descontos até 70%</a></li>
                    <li class="nav-mb-item"><a href="/artigosExclusivosOnline" class="mb-menu-link">Exclusivos online</a></li>
                    <li class="nav-mb-item"><a href="/lojas" class="mb-menu-link">Lojas</a></li>
                    <li class="nav-mb-item"><a href="/contactos" class="mb-menu-link">Contactos</a></li>
                    <li class="nav-mb-item"><a href="/apoioaocliente" class="mb-menu-link">Apoio ao cliente</a></li>
                </ul>
                <div class="group-btn gap-7">
                    <a href="{{ route('wishlist') }}" class="tf-btn rounded-4 style-stroke-3 small gap-10 text-black">
                        <i class="icon icon-Hearth2 fs-14"></i>
                        <span class="text-caption letter-space--3">Favoritos</span>
                    </a>
                    <a href="{{ route('cliente') }}" class="tf-btn rounded-4 style-stroke-3 small gap-10 text-black">
                        <i class="icon icon-User2 fs-14"></i>
                        <span class="text-caption letter-space--3">Conta</span>
                    </a>
                </div>
                <div class="need-help-wrap">
                    <p class="text-caption fw-medium text-decoration-underline mb-15">Precisa de ajuda?</p>
                    <div class="tf-list vertical gap-6">
                        <a href="/lojas" class="d-block text-caption cl-text-4">
                            Loja: <span class="fw-medium">Vendas Novas</span>
                        </a>
                        <a href="mailto:contacto@dfpinteriores.com" class="d-block text-caption cl-text-4">
                            Email: <span class="fw-medium">contacto@dfpinteriores.com</span>
                        </a>
                        <a href="tel:+351912026453" class="d-block text-caption cl-text-4">
                            Telefone: <span class="fw-medium">+351 912 026 453</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-top popup-search full" id="search">
        <div class="btn-close-popup" data-bs-dismiss="offcanvas" aria-label="Fechar pesquisa">
            <i class="icon icon-Close2"></i>
        </div>
        <div class="container">
            <div class="canvas-heading">
                <h4 class="title">O que procura?</h4>
                <form action="{{ route('search') }}" method="get" class="form-search">
                    <input type="text" name="q" placeholder="Sofás, mesas, colchões…" required>
                    <button type="submit" class="btn-action_submit" aria-label="Pesquisar">
                        <i class="icon icon-Search"></i>
                    </button>
                </form>
                <div class="popuplar-search">
                    <span class="text-caption fw-medium">Pesquisas populares:</span>
                    <div class="list-popular">
                        <a href="/sofas" class="popular-item text-caption fw-medium">Sofás</a>
                        <a href="/descontos70" class="popular-item text-caption fw-medium">Descontos</a>
                        <a href="/descanso" class="popular-item text-caption fw-medium">Descanso</a>
                        <a href="/mobiliario" class="popular-item text-caption fw-medium">Mobiliário</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/omniva/js/plugin/bootstrap.min.js"></script>
    <script src="/omniva/js/plugin/jquery.min.js"></script>
    <script src="/omniva/js/plugin/swiper-bundle.min.js"></script>
    <script src="/omniva/js/plugin/bootstrap-select.min.js"></script>
    <script src="/omniva/js/plugin/count-down.js"></script>
    <script src="/omniva/js/plugin/infinityslide.js"></script>
    <script src="/omniva/js/plugin/wow.min.js"></script>
    <script src="/omniva/js/plugin/parallaxie.js"></script>
    <script src="/omniva/js/carousel.js"></script>
    <script src="/omniva/js/main.js"></script>
    <script>
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        document.addEventListener('click', async function (e) {
            const btn = e.target.closest('[data-add-cart]');
            if (!btn) return;
            e.preventDefault();
            const id = btn.getAttribute('data-add-cart');
            try {
                const r = await fetch('/carrinho/add', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ product: id, qtd: '1', _token: token }),
                });
                const data = await r.json();
                const badge = document.getElementById('cart-count-badge');
                if (badge && data.cart) {
                    const n = (data.cart.items || []).reduce((sum, i) => sum + (i.qtd || 0), 0);
                    badge.textContent = n > 0 ? String(n) : '';
                    badge.classList.toggle('is-zero', n <= 0);
                }
                btn.textContent = 'Adicionado';
                setTimeout(() => { btn.textContent = 'Adicionar ao carrinho'; }, 1500);
            } catch (err) {}
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
