<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login DAN rolenya sesuai dengan yang diizinkan
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Jika tidak, tendang ke halaman home
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        return $next($request);
    }
}
