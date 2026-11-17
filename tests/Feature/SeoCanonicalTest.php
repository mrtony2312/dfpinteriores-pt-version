<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Support\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoCanonicalTest extends TestCase
{
    use RefreshDatabase;

    public function test_clean_canonical_strips_query_string(): void
    {
        $this->get('/sofas?preco=119,500&sort=relevante&css=4');

        $this->assertSame(
            'https://dfpinteriores.com/sofas',
            Seo::canonicalForRequest()
        );
        $this->assertTrue(Seo::shouldNoindex());
        $this->assertSame('noindex, follow', Seo::robotsContent());
    }

    public function test_tracking_params_keep_page_indexable(): void
    {
        $this->get('/sofas?utm_source=google&gclid=abc');

        $this->assertSame(
            'https://dfpinteriores.com/sofas',
            Seo::canonicalForRequest()
        );
        $this->assertFalse(Seo::shouldNoindex());
    }

    public function test_private_paths_are_noindex(): void
    {
        $this->get('/carrinho');
        $this->assertTrue(Seo::shouldNoindex());

        $this->get('/procura?q=sofa');
        $this->assertTrue(Seo::shouldNoindex());
    }

    public function test_sitemap_lists_clean_urls_including_categories(): void
    {
        Category::query()->create([
            'id' => 1,
            'name' => 'Sofás',
            'slug' => 'sofas',
            'parent_id' => null,
        ]);
        Category::query()->create([
            'id' => 2,
            'name' => 'Vazia',
            'slug' => 'vazia',
            'parent_id' => null,
        ]);
        Product::factory()->create([
            'category_id' => 1,
            'category_slug' => 'sofas',
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $body = $response->streamedContent();

        $this->assertStringContainsString('https://dfpinteriores.com/</loc>', $body);
        $this->assertStringContainsString('https://dfpinteriores.com/sofas</loc>', $body);
        $this->assertStringNotContainsString('https://dfpinteriores.com/vazia</loc>', $body);
        $this->assertStringContainsString('https://dfpinteriores.com/descontos70</loc>', $body);
        $this->assertDoesNotMatchRegularExpression('/<loc>[^<]*\?[^<]*<\/loc>/', $body);
    }

    public function test_absolute_helper_normalizes_paths(): void
    {
        $this->assertSame('https://dfpinteriores.com/', Seo::absolute('/'));
        $this->assertSame('https://dfpinteriores.com/sofas', Seo::absolute('sofas'));
        $this->assertSame('https://dfpinteriores.com/sofas', Seo::absolute('/sofas/'));
    }
}
