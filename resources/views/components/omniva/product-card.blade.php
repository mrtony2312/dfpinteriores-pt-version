@props(['product', 'badge' => null])
@php
    $p = $product;
    $imgs = $p->relationLoaded('images') ? $p->images : $p->images()->get();
    $img = $imgs->first();
    $hover = $imgs->skip(1)->first() ?: $img;
    $preco = (float) $p->price;
    $antes = (float) ($p->price_before ?? 0);
    $temDesconto = $antes > $preco && $preco > 0;
    $pct = $temDesconto ? (int) round((($antes - $preco) / $antes) * 100) : 0;
    $src = $img ? asset($img->path) : '/assets/images/no-photo.svg';
    $srcHover = $hover ? asset($hover->path) : $src;
@endphp
<div class="card-product wow fadeInUpBlur">
    <div class="card-product_wrapper aspect-ratio-1">
        <a href="{{ url('/'.$p->slug) }}" class="product-img">
            <img class="img-product" loading="lazy" width="331" height="389"
                 src="{{ $src }}" alt="{{ $p->name }}"
                 onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
            <img class="img-hover" loading="lazy" width="331" height="389"
                 src="{{ $srcHover }}" alt="{{ $p->name }}"
                 onerror="this.onerror=null;this.src='/assets/images/no-photo.svg'">
        </a>
        <ul class="product-action_list">
            <li class="wishlist">
                <a href="{{ route('wishlist') }}" class="hover-tooltip tooltip-left box-icon" data-wishlist-toggle="{{ $p->id }}">
                    <span class="icon icon-Hearth2"></span>
                    <span class="tooltip">Favoritos</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/'.$p->slug) }}" class="hover-tooltip tooltip-left box-icon">
                    <span class="icon icon-EyeStroke"></span>
                    <span class="tooltip">Ver produto</span>
                </a>
            </li>
        </ul>
        @if ($temDesconto || $badge)
            <ul class="product-badge_list">
                @if ($temDesconto)
                    <li class="product-badge_item text-caption sale">-{{ $pct }}%</li>
                @elseif ($badge === 'new')
                    <li class="product-badge_item text-caption new">Novo</li>
                @elseif ($badge === 'best')
                    <li class="product-badge_item text-caption best">
                        <i class="icon icon-Thunder fs-12 tf-ani-flash"></i> Top
                    </li>
                @endif
            </ul>
        @endif
        <div class="product-action_bot">
            <a href="#" class="btn-action_choose text-caption" data-add-cart="{{ $p->id }}">
                Adicionar ao carrinho
            </a>
        </div>
    </div>
    <div class="card-product_info">
        <a href="{{ url('/'.$p->slug) }}" class="name-product fw-medium link-underline">
            {{ $p->name }}
        </a>
        @if ($p->rating)
            <div class="star-wrap fs-14 style-yellow">
                @for ($i = 0; $i < 5; $i++)
                    <i class="icon icon-Star{{ $i < round($p->rating) ? '' : '2' }}"></i>
                @endfor
            </div>
        @endif
        <div class="price-wrap">
            <span class="price-new h6 text-primary">
                {{ $preco > 0 ? number_format($preco, 2, ',', ' ').'€' : 'Sob consulta' }}
            </span>
            @if ($temDesconto)
                <span class="price-old text-caption fw-medium">{{ number_format($antes, 2, ',', ' ') }}€</span>
            @endif
        </div>
    </div>
</div>
