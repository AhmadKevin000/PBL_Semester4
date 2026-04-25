<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsGuru
{
    /**
     * Pastikan user yang mengakses panel guru memiliki role 'guru'.
     * Jika bukan guru, redirect ke halaman login panel guru.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'guru') {
            auth()->logout();

            return redirect()->route('filament.guru.auth.login')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Guru.');
        }

        return $next($request);
    }
}
