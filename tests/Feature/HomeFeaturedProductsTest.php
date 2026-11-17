<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeFeaturedProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_bed_offers_then_mattresses_after_furniture_banner(): void
    {
        $beds = $this->category(501, 'Camas', 'camas');
        $mattresses = $this->category(502, 'Colchões', 'colchoes');

        foreach (range(1, 4) as $n) {
            $this->product($beds, 'Cama Oferta '.$n, 'cama-oferta-'.$n, 200, 400);
        }
        $this->product($beds, 'Cama Sem Foto', 'cama-sem-foto', 100, 300, false);

        foreach (range(1, 4) as $n) {
            $this->product($mattresses, 'Colchao Selecao '.$n, 'colchao-selecao-'.$n, 150, 180);
        }

        $html = $this->get('/')->assertOk()->getContent();

        $banner = strpos($html, 'dfp-promo-banner--wide');
        $bedsHeading = strpos($html, 'Ofertas em camas');
        $mattressHeading = strpos($html, 'id="colchoes-home"');

        $this->assertNotFalse($banner);
        $this->assertNotFalse($bedsHeading);
        $this->assertNotFalse($mattressHeading);
        $this->assertLessThan($bedsHeading, $banner);
        $this->assertLessThan($mattressHeading, $bedsHeading);

        foreach (range(1, 4) as $n) {
            $this->assertStringContainsString('Cama Oferta '.$n, $html);
            $this->assertStringContainsString('Colchao Selecao '.$n, $html);
        }
        $this->assertStringNotContainsString('Cama Sem Foto', $html);
        $this->assertStringContainsString('href="/camas"', $html);
        $this->assertStringContainsString('href="/colchoes"', $html);
    }

    private function category(int $id, string $name, string $slug): Category
    {
        return Category::query()->create([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'parent_id' => null,
            'level' => 1,
        ]);
    }

    private function product(Category $category, string $name, string $slug, float $price, float $before, bool $withImage = true): Product
    {
        $product = Product::factory()->create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'category_slug' => $category->slug,
            'category_name' => $category->name,
            'price' => $price,
            'price_before' => $before,
        ]);

        if ($withImage) {
            ProductImage::factory()->create(['product_id' => $product->id]);
        }

        return $product;
    }
}
