@props(['products'])

@if ($products->isNotEmpty())
<section class="flat-spacing pb-0">
    <div class="container">
        <div class="text-center spacing-bottom-2">
            <h3 class="mb-4">Produtos semelhantes</h3>
            <p class="text-caption cl-text-4 mb-0">Da mesma categoria</p>
        </div>
        <div class="tf-grid-layout tf-col-2 md-col-3 lg-col-4">
            @foreach ($products->take(8) as $related)
                <x-omniva.product-card :product="$related" />
            @endforeach
        </div>
    </div>
</section>
@endif
