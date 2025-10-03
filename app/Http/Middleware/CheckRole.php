<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::warning('CheckRole middleware: User not authenticated', [
                'url' => $request->url(),
                'role_required' => $role,
                'session_id' => session()->getId()
            ]);
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah role user sesuai
        if (Auth::user()->role !== $role) {
            Log::warning('CheckRole middleware: Role mismatch', [
                'user_role' => Auth::user()->role,
                'required_role' => $role,
                'url' => $request->url(),
                'user_id' => Auth::user()->id
            ]);
            // Redirect berdasarkan role user
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.events.index')->with('error', 'Akses ditolak. Hanya peserta yang diizinkan.');
            }
            return redirect()->route('participant.events.index')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk fitur ini.');
        }

        Log::info('CheckRole middleware: Access granted', [
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'url' => $request->url(),
            'session_id' => session()->getId(),
            'auth_check' => Auth::check(),
            'guard_check' => Auth::guard('web')->check()
        ]);

        return $next($request);
    }
}
