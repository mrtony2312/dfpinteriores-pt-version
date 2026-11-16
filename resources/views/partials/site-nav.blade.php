{{-- Navigation commune (identique à la home Omniva) --}}
<div class="dfp-site-nav">
<div class="tf-topbar bg-dfp sm-d-none">
    <div class="container-full">
        <div class="row">
            <div class="col-lg-6">
                <div class="tf-btn-swiper-main">
                    <div class="text-white fs-18 nav-prev-swiper"><i class="icon icon-ArrowCaretLeft"></i></div>
                    <div dir="ltr" class="swiper tf-swiper swiper-topbar" data-touch="false" data-auto="true" data-loop="true" data-speed="1500">
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
                            @foreach (($navCategories ?? collect())->take(6) as $cat)
                                <li class="menu-item">
                                    <a href="/{{ $cat->slug }}" class="item-link">
                                        <span class="text">{{ $cat->name }}</span>
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
                                                            <span class="text">{{ $child->name }}</span>
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

@include('partials.shipping-marquee')

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
                @foreach (($navCategories ?? collect())->take(8) as $cat)
                    <li class="nav-mb-item">
                        <a href="/{{ $cat->slug }}" class="mb-menu-link">{{ $cat->name }}</a>
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
</div>
