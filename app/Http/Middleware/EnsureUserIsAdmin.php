<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id === 1) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Acceso denegado. Solo el Administrador puede realizar esta acción.');
    }
}
