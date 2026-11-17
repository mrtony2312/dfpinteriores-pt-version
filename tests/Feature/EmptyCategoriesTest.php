<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmptyCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_navigation_search_and_pages_skip_categories_without_products(): void
    {
        $sofas = $this->category(10, 'Sofás', 'sofas');
        $chaise = $this->category(11, 'Chaise longue', 'chaise-longue', $sofas->id, 2);
        $emptyChild = $this->category(12, 'Sem stock', 'sem-stock', $sofas->id, 2);
        $emptyRoot = $this->category(13, 'Electrodomesticos vazios', 'electrodomesticos-vazios');
        $pivotOnly = $this->category(14, 'Jardim', 'jardim');

        Product::factory()->create([
            'name' => 'Sofá Presente',
            'slug' => 'sofa-presente',
            'category_id' => $chaise->id,
            'category_slug' => $chaise->slug,
        ]);

        $linked = Product::factory()->create([
            'name' => 'Mesa Jardim',
            'slug' => 'mesa-jardim',
            'category_id' => null,
        ]);
        $pivotOnly->allProducts()->attach($linked->id, ['depth' => 0]);

        $home = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Sofás', $home);
        $this->assertStringContainsString('Chaise longue', $home);
        $this->assertStringContainsString('Jardim', $home);
        $this->assertStringNotContainsString('Sem stock', $home);
        $this->assertStringNotContainsString('Electrodomesticos vazios', $home);

        $this->get('/sofas')->assertOk()->assertSee('Sofá Presente');
        $this->get('/chaise-longue')->assertOk();
        $this->get('/jardim')->assertOk()->assertSee('Mesa Jardim');
        $this->get('/sem-stock')->assertNotFound();
        $this->get('/electrodomesticos-vazios')->assertNotFound();

        $suggest = $this->get('/procura/sugestoes?q=vaz')->assertOk()->json();
        $names = array_column($suggest['results'][0]['hits'], 'name');
        $this->assertNotContains('Electrodomesticos vazios', $names);

        $sofasSuggest = $this->get('/procura/sugestoes?q=sof')->assertOk()->json();
        $this->assertContains('Sofás', array_column($sofasSuggest['results'][0]['hits'], 'name'));
    }

    public function test_ver_tudo_departments_show_their_real_name(): void
    {
        $sofas = $this->category(1, 'Ver tudo', 'sofas');
        Product::factory()->create([
            'name' => 'Sofá Presente',
            'slug' => 'sofa-presente',
            'category_id' => $sofas->id,
            'category_slug' => $sofas->slug,
        ]);

        $home = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('Sofás', $home);
        $this->assertStringNotContainsString('Ver tudo', $home);

        $this->get('/sofas')->assertOk()->assertSee('<h1>Sofás</h1>', false);
    }

    public function test_duplicate_department_names_appear_once_in_the_menu(): void
    {
        $rooms = $this->category(900004, 'Ver tudo', 'mobiliario');
        $extra = $this->category(293, 'MOBILIÁRIO', 'mobiliario-2');
        Product::factory()->create([
            'category_id' => $rooms->id,
            'slug' => 'mesa-sala',
            'name' => 'Mesa Sala',
        ]);
        Product::factory()->create([
            'category_id' => $extra->id,
            'slug' => 'estante-extra',
            'name' => 'Estante Extra',
        ]);

        $html = $this->get('/')->assertOk()->getContent();
        preg_match('/<ul class="box-nav-menu">.*?<\/ul>/s', $html, $menu);

        $this->assertSame(1, substr_count($menu[0], 'Mobiliário'));
        $this->assertStringNotContainsString('MOBILIÁRIO', $menu[0]);
    }

    private function category(int $id, string $name, string $slug, ?int $parentId = null, int $level = 1): Category
    {
        return Category::query()->create([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
            'level' => $level,
        ]);
    }
}
