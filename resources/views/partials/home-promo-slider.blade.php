{{-- Ancien carrousel promo animé (liens directs) + bandeaux secondaires --}}
@php
    $promoSlides = [
        [
            'href' => '/lojas',
            'alt' => 'Grande Festa Sem IVA — Matosinhos e Montijo',
            'file' => 'grande_festa_sem_iva',
            'eager' => true,
        ],
        [
            'href' => '/descontos70',
            'alt' => 'Black Fridays — descontos até 70%',
            'file' => 'black_fridays',
        ],
    ];

@endphp

<section class="dfp-promo-hero" aria-label="Promoções">
    <div class="dfp-promo-hero__banner">
        <div dir="ltr" class="swiper tf-swiper dfp-promo-swiper" data-auto="true" data-loop="true" data-delay="1" data-speed="9000" data-pause-hover="false">
            <div class="swiper-wrapper">
                @foreach ($promoSlides as $i => $slide)
                    <div class="swiper-slide">
                        <a href="{{ $slide['href'] }}" class="dfp-promo-hero__slide">
                            @if (! empty($slide['file']))
                                <img
src="/media/banners/{{ $slide['file'] }}.jpg?v=20261116"
                                    alt="{{ $slide['alt'] }}"
                                    width="1024"
                                    height="1024"
                                    @if (! empty($slide['eager']))
                                        loading="eager" fetchpriority="high"
                                    @else
                                        loading="lazy"
                                    @endif
                                    decoding="async"
                                >
                            @else
                                <picture>
                                    <source media="(max-width: 768px)" type="image/avif" srcset="/media/banners/{{ $slide['mobile'] }}.avif">
                                    <source media="(max-width: 768px)" type="image/webp" srcset="/media/banners/{{ $slide['mobile'] }}.webp">
                                    <source media="(max-width: 768px)" type="image/jpeg" srcset="/media/banners/{{ $slide['mobile'] }}.jpg">
                                    <source type="image/avif" srcset="/media/banners/{{ $slide['desktop'] }}.avif">
                                    <source type="image/webp" srcset="/media/banners/{{ $slide['desktop'] }}.webp">
                                    <img
                                        src="/media/banners/{{ $slide['desktop'] }}.jpg"
                                        alt="{{ $slide['alt'] }}"
                                        width="1920"
                                        height="621"
                                        @if (! empty($slide['eager']))
                                            loading="eager" fetchpriority="high"
                                        @else
                                            loading="lazy"
                                        @endif
                                        decoding="async"
                                    >
                                </picture>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="dfp-promo-hero__pag">
                <div class="sw-line-default style-white tf-sw-pagination size2"></div>
            </div>
            <button type="button" class="dfp-promo-hero__nav dfp-promo-hero__nav--prev nav-prev-swiper" aria-label="Anterior">
                <i class="icon icon-ArrowCaretLeft"></i>
            </button>
            <button type="button" class="dfp-promo-hero__nav dfp-promo-hero__nav--next nav-next-swiper" aria-label="Seguinte">
                <i class="icon icon-ArrowCaretRight"></i>
            </button>
        </div>
    </div>

    <div class="container dfp-promo-hero__video">
        <video
            id="dfp-promo-video"
            class="dfp-promo-hero__player"
            width="100%"
            preload="auto"
            autoplay
            muted
            loop
            playsinline
            webkit-playsinline
            disablepictureinpicture
            controlslist="nodownload noplaybackrate"
        >
            <source src="/videos/promo.mp4?v=5" type="video/mp4">
        </video>
    </div>
</section>

<script>
(function () {
    var keepMoving = function () {
        var el = document.querySelector('.dfp-promo-swiper');
        var swiper = el && el.swiper;
        if (!swiper || !swiper.autoplay) return false;
        swiper.params.speed = 9000;
        swiper.params.loop = true;
        swiper.params.autoplay.delay = 1;
        swiper.params.autoplay.disableOnInteraction = false;
        swiper.params.autoplay.pauseOnMouseEnter = false;
        swiper.wrapperEl.style.transitionTimingFunction = 'linear';
        swiper.autoplay.stop();
        swiper.autoplay.start();
        return true;
    };

    window.addEventListener('load', function () {
        if (!keepMoving()) {
            setTimeout(keepMoving, 400);
        }
    });
})();
</script>

<script>
(function () {
    var video = document.getElementById('dfp-promo-video');
    if (!video) return;

    var tryPlay = function () {
        try {
            video.muted = true;
            video.defaultMuted = true;
            video.setAttribute('muted', '');
            video.playsInline = true;
            var p = video.play();
            if (p && typeof p.then === 'function') {
                p.catch(function () { /* autoplay bloqueado até interação */ });
            }
        } catch (e) {}
    };

    if (video.readyState >= 2) {
        tryPlay();
    } else {
        video.addEventListener('loadeddata', tryPlay, { once: true });
        video.addEventListener('canplay', tryPlay, { once: true });
    }

    document.addEventListener('DOMContentLoaded', tryPlay);
    window.addEventListener('load', tryPlay);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) tryPlay();
    });

    // iOS / Chrome mobile: relancer à la 1ère interaction si besoin
    var unlock = function () {
        tryPlay();
        document.removeEventListener('touchstart', unlock);
        document.removeEventListener('click', unlock);
        document.removeEventListener('scroll', unlock);
    };
    document.addEventListener('touchstart', unlock, { once: true, passive: true });
    document.addEventListener('click', unlock, { once: true });
    document.addEventListener('scroll', unlock, { once: true, passive: true });

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) tryPlay();
            });
        }, { threshold: 0.15 });
        io.observe(video);
    }
})();
</script>
