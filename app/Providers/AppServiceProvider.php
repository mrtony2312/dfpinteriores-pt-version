<?php

namespace App\Providers;

use App\Domain\Merchant\MerchantPolicy;
use App\Models\Category;
use App\Services\CartService;
use App\Services\WishlistService;
use App\Support\Seo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $cart = app(CartService::class);
            $wishlist = app(WishlistService::class);

            $view->with([
                'cartData' => $cart->toArray(),
                'cartCount' => $cart->count(),
                'wishCount' => $wishlist->count(),
                'wishIds' => $wishlist->ids(),
                'authUser' => auth()->user(),
                'organizationSchema' => MerchantPolicy::organizationSchema(),
                'merchantDelivery' => MerchantPolicy::deliveryCopy(),
                'robots' => Seo::robotsContent(),
            ]);
            if (! $view->offsetExists('canonical')) {
                $view->with('canonical', Seo::canonicalForRequest());
            }

            if (! Schema::hasTable('categories')) {
                return;
            }
            $withProducts = Category::idsWithProducts();
            $preferred = [
                'sofas',
                'mobiliario',
                'decoracao',
                'descanso',
                'mobiliario-de-exterior',
                'mobiliario-2',
                'decoracao-1',
            ];
            $seen = [];
            $view->with('navCategories', Category::query()
                ->whereNull('parent_id')
                ->whereIn('id', $withProducts)
                ->with(['children' => fn ($children) => $children->whereIn('categories.id', $withProducts)])
                ->get()
                ->sortBy(function (Category $category) use ($preferred): int {
                    $index = array_search($category->slug, $preferred, true);

                    return $index === false ? 100 : $index;
                })
                ->filter(function (Category $category) use (&$seen): bool {
                    $key = mb_strtolower($category->displayName());
                    if (isset($seen[$key])) {
                        return false;
                    }
                    $seen[$key] = true;

                    return true;
                })
                ->values());
        });
        //
    }
}
