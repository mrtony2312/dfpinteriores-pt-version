<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Canonicalisation et directives robots pour Google Search Console.
 *
 * Problèmes GSC ciblés :
 * - pages en double sans canonique utilisateur
 * - Google choisit une autre canonique
 * - pages filtrées / paramètres explorées mais non indexées
 */
final class Seo
{
    /** Paramètres de tracking autorisés (la page reste indexable, canonique propre). */
    private const TRACKING_PARAMS = [
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'gclid', 'gbraid', 'wbraid', 'fbclid', 'msclkid', '_ga', 'mc_cid', 'mc_eid',
    ];

    /** Préfixes d'URL à ne jamais indexer. */
    private const NOINDEX_PREFIXES = [
        'carrinho',
        'cliente',
        'wishlist',
        'procura',
        'admin',
        '_more',
    ];

    public static function baseUrl(): string
    {
        return rtrim((string) config('feed.base_url', config('app.url', 'https://dfpinteriores.com')), '/');
    }

    /**
     * URL canonique absolue HTTPS, sans query string ni fragment.
     */
    public static function absolute(string $path = ''): string
    {
        $path = trim($path);
        if ($path === '' || $path === '/') {
            return self::baseUrl().'/';
        }

        $path = '/'.ltrim($path, '/');
        $path = rtrim($path, '/');

        return self::baseUrl().$path;
    }

    /**
     * Canonique de la requête courante (chemin seul).
     */
    public static function canonicalForRequest(?Request $request = null): string
    {
        $request ??= request();
        $path = '/'.ltrim($request->path(), '/');
        if ($path === '//') {
            $path = '/';
        }

        return self::absolute($path === '/' ? '' : $path);
    }

    /**
     * true si la page ne doit pas être indexée (compte, panier, filtres catalogue, etc.).
     */
    public static function shouldNoindex(?Request $request = null): bool
    {
        $request ??= request();
        $path = trim($request->path(), '/');

        foreach (self::NOINDEX_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return self::hasIndexAffectingQuery($request);
    }

    public static function robotsContent(?Request $request = null): string
    {
        return self::shouldNoindex($request)
            ? 'noindex, follow'
            : 'index, follow';
    }

    /**
     * Query params hors tracking (filtres prix, sort, page, css=…).
     */
    public static function hasIndexAffectingQuery(?Request $request = null): bool
    {
        $request ??= request();
        foreach (array_keys($request->query()) as $key) {
            $key = strtolower((string) $key);
            if (in_array($key, self::TRACKING_PARAMS, true)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
