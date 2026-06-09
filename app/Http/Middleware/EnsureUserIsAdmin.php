<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autorización por rol — CU02.
 * 
 * Verifica que el usuario autenticado tenga role_id === 1 (Administrador).
 * Si no cumple, redirige al dashboard con un mensaje flash de error.
 * Registrado como alias 'admin' en Kernel.php.
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo permite continuar si el usuario está autenticado y es admin (role_id = 1)
        if (auth()->check() && auth()->user()->hasRole('Administrador')) {
            return $next($request);
        }

        // Redirige con mensaje flash amigable en lugar de abort(403)
        return redirect()->route('dashboard')->with('error', 'Acceso denegado. Solo el Administrador puede realizar esta acción.');
    }
}
