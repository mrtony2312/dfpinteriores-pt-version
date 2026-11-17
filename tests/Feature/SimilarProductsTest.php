<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SimilarProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_lists_same_category_and_excludes_the_current_product(): void
    {
        $sofas = $this->category(10, 'Sofás', 'sofas');
        $tables = $this->category(11, 'Mesas', 'mesas');
        $current = $this->productIn($sofas, 'Sofá Atual');
        $sibling = $this->productIn($sofas, 'Sofá Irmão');
        $other = $this->productIn($tables, 'Mesa Alheia');

        $html = $this->get('/'.$current->slug)->assertOk()->getContent();
        $section = $this->similarSection($html);

        $this->assertStringContainsString('Produtos semelhantes', $section);
        $this->assertStringContainsString('Da mesma categoria', $section);
        $this->assertStringContainsString($sibling->name, $section);
        $this->assertStringNotContainsString($current->name, $section);
        $this->assertStringNotContainsString($other->name, $section);
    }

    public function test_confirmation_lists_products_from_the_ordered_category(): void
    {
        Mail::fake();

        $sofas = $this->category(20, 'Sofás', 'sofas-encomenda');
        $tables = $this->category(21, 'Mesas', 'mesas-encomenda');
        $ordered = $this->productIn($sofas, 'Sofá Encomendado');
        $sibling = $this->productIn($sofas, 'Sofá Parecido');
        $other = $this->productIn($tables, 'Mesa Fora');

        $this->app->make(CartService::class)->add($ordered->id, null, 1);

        $this->post(route('checkout.place'), [
            'name' => 'Cliente Teste',
            'email' => 'cliente@example.com',
            'phone' => '912026453',
            'address' => 'Rua Teste 1',
            'postal_code' => '7080-035',
            'city' => 'Vendas Novas',
            'payment_method' => 'VIREMENT',
            'terms' => '1',
        ])->assertRedirect();

        $html = $this->get(route('checkout.success', Order::query()->first()->ref))
            ->assertOk()
            ->assertSee('Escolher comprovativo', false)
            ->assertSee('Nenhum ficheiro escolhido', false)
            ->getContent();

        $section = $this->similarSection($html);
        $this->assertStringContainsString($sibling->name, $section);
        $this->assertStringNotContainsString($ordered->name, $section);
        $this->assertStringNotContainsString($other->name, $section);
    }

    private function category(int $id, string $name, string $slug): Category
    {
        return Category::query()->create([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'level' => 1,
        ]);
    }

    private function productIn(Category $category, string $name): Product
    {
        $product = Product::factory()->create([
            'name' => $name,
            'slug' => str($name)->slug().'-'.$category->id,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'category_slug' => $category->slug,
            'price' => 150,
        ]);

        ProductImage::factory()->create(['product_id' => $product->id]);

        return $product;
    }

    private function similarSection(string $html): string
    {
        $start = strpos($html, 'Produtos semelhantes');
        $this->assertNotFalse($start);

        return substr($html, $start);
    }
}
