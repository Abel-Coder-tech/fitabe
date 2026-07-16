<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Vérifie que l'utilisateur authentifié possède le rôle requis.
     * Usage dans les routes : ->middleware('role:super_admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
