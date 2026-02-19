<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return match($role) {
                'admin'  => redirect()->route('admin.login'),
                'client' => redirect()->route('client.login'),
                default  => redirect('/'),
            };
        }

        if (Auth::user()->role !== $role) {
            Auth::logout();
            return match($role) {
                'admin'  => redirect()->route('admin.login')->withErrors(['role' => 'Accès non autorisé.']),
                'client' => redirect()->route('client.login')->withErrors(['role' => 'Accès non autorisé.']),
                default  => redirect('/'),
            };
        }

        return $next($request);
    }
}