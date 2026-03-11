<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Update last_seen_at setiap 1 menit (throttle agar tidak excessive DB write)
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinute())) {
                $user->updateQuietly(['last_seen_at' => now()]);
            }

            // Blokir user yang dinonaktifkan admin (kecuali admin itu sendiri & halaman logout)
            if (
                !$user->is_active &&
                !$user->isAdmin() &&
                !$request->routeIs('logout') &&
                !$request->routeIs('login') &&
                !$request->routeIs('password.*')
            ) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi admin.']);
            }
        }

        return $next($request);
    }
}
