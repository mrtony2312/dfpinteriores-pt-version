@php
    $items = [
        ['icon' => 'icon-Truck', 'html' => '<strong>Entrega gratuita</strong> em todo o Portugal'],
        ['icon' => 'icon-Package', 'html' => 'Envio em <strong>1 a 3 dias úteis</strong>'],
        ['icon' => 'icon-Location', 'html' => 'Portugal continental — <strong>portes grátis</strong>'],
    ];
@endphp
<div class="dfp-shipping-marquee" role="region" aria-label="Entrega gratuita em todo o Portugal">
    <div class="dfp-shipping-marquee__track">
        @foreach ([false, true] as $clone)
            <div class="dfp-shipping-marquee__group" @if ($clone) aria-hidden="true" @endif>
                @for ($r = 0; $r < 4; $r++)
                    @foreach ($items as $item)
                        <span class="dfp-shipping-marquee__item">
                            <i class="dfp-shipping-marquee__icon icon {{ $item['icon'] }}" aria-hidden="true"></i>
                            <span>{!! $item['html'] !!}</span>
                        </span>
                        <span class="dfp-shipping-marquee__sep" aria-hidden="true">·</span>
                    @endforeach
                @endfor
            </div>
        @endforeach
    </div>
</div>
