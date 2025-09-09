<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah role user sesuai
        if (Auth::user()->role !== $role) {
            // Redirect berdasarkan role user
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.events.index')->with('error', 'Akses ditolak. Hanya peserta yang diizinkan.');
            }
            return redirect()->route('participant.events.index')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk fitur ini.');
        }

        return $next($request);
    }
}