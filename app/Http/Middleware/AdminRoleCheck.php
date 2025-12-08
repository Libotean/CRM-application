<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleCheck
{
    /**
    * Gestioneaza o cerere care intra in aplicatie.
    *
    * Middleware-ul acesta actioneaza ca un gardian pentru rutele destinate exclusiv administratorilor.
    * 1. Verifica intai daca utilizatorul este autentificat in sistem.
    * 2. Verifica daca utilizatorul autentificat are rolul specific de 'admin'.
    *
    * Daca utilizatorul nu este logat, este trimis la Login.
    * Daca este logat dar nu e admin, este trimis pe Dashboard cu eroare.
    * Daca trece de verificari, cererea merge mai departe catre Controller ($next).
    *
    * @param  \Illuminate\Http\Request  $request  Cererea HTTP curenta
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  Functia care permite trecerea la urmatorul middleware sau controller
    * @return \Symfony\Component\HttpFoundation\Response  Returneaza fie o redirectionare (in caz de esec), fie raspunsul aplicatiei (in caz de succes)
    */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect('/')->with('error', 'Access denied');
        }

        return $next($request);
    }
}
