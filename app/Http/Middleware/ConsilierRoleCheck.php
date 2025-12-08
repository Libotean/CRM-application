<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConsilierRoleCheck
{
    /**
    * Gestioneaza accesul pentru rutele dedicate Consilierilor de vanzari.
    *
    * Acest middleware actioneaza ca un filtru de securitate pentru zona operativa a aplicatiei.
    * Asigura ca doar utilizatorii autentificati si care au rolul specific de 'user'
    * pot accesa functionalitatile de gestionare clienti si lead-uri.
    *
    * Logica de verificare:
    * 1. Autentificare: Daca utilizatorul nu e logat face Redirect la Login.
    * 2. Autorizare: Daca utilizatorul e logat dar are alt rol face Redirect cu eroare.
    *
    * @param  \Illuminate\Http\Request  $request  Cererea HTTP
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function handle(Request $request, Closure $next): Response
    {
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
