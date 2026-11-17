<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'int';

    protected $guarded = [];

    protected $casts = [
        'dimensions' => 'array',
        'variations' => 'array',
        'flags' => 'array',
        'breadcrumb' => 'array',
        'category_path' => 'array',
        'source_payload' => 'array',
        'price' => 'decimal:2',
        'price_before' => 'decimal:2',
        'in_stock' => 'boolean',
        'rating' => 'float',
        'source_created_at' => 'datetime',
        'source_updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')->withPivot('depth');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('position');
    }

    public function extraServices(): HasMany
    {
        return $this->hasMany(ProductExtraService::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->images->first()?->path;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->price_before !== null && (float) $this->price_before > (float) $this->price;
    }

    /**
     * Produits des mêmes catégories, hors articles déjà affichés ou commandés.
     *
     * @param  iterable<int, Product>  $sources
     */
    public static function similarTo(iterable $sources, int $limit = 8): Collection
    {
        $sources = collect($sources)->filter(fn ($product) => $product instanceof self)->values();
        if ($sources->isEmpty()) {
            return new Collection;
        }

        $exclude = $sources->pluck('id')->map(fn ($id) => (int) $id)->all();
        $categoryIds = $sources->flatMap(function (self $product): array {
            $ids = [(int) $product->category_id];
            if ($product->relationLoaded('categories')) {
                foreach ($product->categories as $category) {
                    $ids[] = (int) $category->id;
                }
            }

            return $ids;
        })->filter()->unique()->values()->all();

        if ($categoryIds === []) {
            return new Collection;
        }

        return static::query()
            ->with('images')
            ->where(function ($query) use ($categoryIds): void {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereHas('categories', fn ($categories) => $categories->whereIn('categories.id', $categoryIds));
            })
            ->whereNotIn('id', $exclude)
            ->where('price', '>', 0)
            ->whereHas('images')
            ->limit(min(8, max(1, $limit)))
            ->inRandomOrder()
            ->get();
    }
}
