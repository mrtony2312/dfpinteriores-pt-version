<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

/**
 * CSRF actif partout, sauf sur les POST « lecture seule » du catalogue
 * (filtres ListaArtigos) qui n'envoient pas de token Laravel.
 * Les routes admin / checkout / auth / panier restent protégées.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * @var list<string>
     */
    protected $except = [
        'descontos70',
        'artigosExclusivosOnline',
        'procura',
        'procura/*',
    ];

    protected function inExceptArray($request): bool
    {
        if (parent::inExceptArray($request)) {
            return true;
        }

        // POST filtres catalogue sur /{slug} (catégorie) — jamais les mutations métier.
        if ($request->isMethod('POST') && $request->routeIs('show', 'listing.more', 'descontos70', 'exclusivos', 'search', 'search.term')) {
            return true;
        }

        return false;
    }

}
