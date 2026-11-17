<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Category extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $guarded = [];

    protected $casts = [
        'level' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    /** Produits dont c'est la catégorie feuille. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /** Tous les produits rattachés (via le fil d'Ariane complet). */
    public function allProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product')->withPivot('depth');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Nom affiché. Les entrées « Ver tudo » du catalogue source sont les rayons
     * (le vrai libellé est le slug : sofas, descanso, mobiliario…).
     */
    public function displayName(): string
    {
        $departments = [
            'sofas' => 'Sofás',
            'mobiliario' => 'Mobiliário',
            'decoracao' => 'Decoração',
            'descanso' => 'Descanso',
            'mobiliario-de-exterior' => 'Exterior',
        ];

        if (isset($departments[$this->slug])) {
            return $departments[$this->slug];
        }

        if (mb_strtolower(trim((string) $this->name)) === 'ver tudo') {
            return Str::title(str_replace('-', ' ', (string) $this->slug));
        }

        return (string) $this->name;
    }

    /**
     * Catégories qui ont au moins un produit, elles-mêmes ou via un descendant.
     *
     * @return array<int, int>
     */
    public static function idsWithProducts(): array
    {
        $request = request();
        if ($request->attributes->has('category_ids_with_products')) {
            return $request->attributes->get('category_ids_with_products');
        }

        if (! Schema::hasTable('products')) {
            $request->attributes->set('category_ids_with_products', []);

            return [];
        }

        $ids = Product::query()
            ->whereNotNull('category_id')
            ->distinct()
            ->pluck('category_id')
            ->map(fn ($id) => (int) $id);

        if (Schema::hasTable('category_product')) {
            $ids = $ids->merge(
                DB::table('category_product')->distinct()->pluck('category_id')->map(fn ($id) => (int) $id)
            );
        }

        $parents = self::query()->pluck('parent_id', 'id');
        $keep = [];

        foreach ($ids->unique() as $id) {
            $cursor = (int) $id;
            $guard = 0;
            while ($cursor > 0 && $guard++ < 12) {
                $keep[$cursor] = $cursor;
                $parent = $parents[$cursor] ?? null;
                $cursor = $parent ? (int) $parent : 0;
            }
        }

        $result = array_values($keep);
        $request->attributes->set('category_ids_with_products', $result);

        return $result;
    }
}
