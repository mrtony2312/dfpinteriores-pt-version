<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->is_admin) {
            if ($request->expectsJson()) {
                abort(403, 'Acesso recusado.');
            }

            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
