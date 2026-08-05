<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie que l'utilisateur est connecté ET a le rôle admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied: this action is restricted to administrators.');
        }

        return $next($request);
    }
    
}
