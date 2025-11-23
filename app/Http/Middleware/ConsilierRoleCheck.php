<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConsilierRoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifică dacă user-ul este logat și NU e admin
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user || $user->role !== 'user') {
            return redirect('/')->with('error', 'Access denied');
        }
        return $next($request);

    }
}
