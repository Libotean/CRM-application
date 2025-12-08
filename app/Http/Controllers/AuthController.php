<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Afiseaza formularul de autentificare.
     *
     * @return \Illuminate\View\View Pagina de login.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Procesare cerere de autentificare a utilizatorului.
     *
     * Pasii efectuati:
     * - validare date introduse (email, parola)
     * - verificare daca utilizatorul exista
     * - verificare parola folosind Hash::check
     * - verificare daca utilizatorul este activ sau expirat
     * - autentificare utilizator si il redirectionare spre dashboard
     *
     * @param \Illuminate\Http\Request $request Datele introduse in formularul de login.
     * @return \Illuminate\Http\RedirectResponse Redirect inapoi cu erori sau catre pagina principala.
     */
    public function login(Request $request)
    {
        // Validare date login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificam daca utilizatorul exista in baza de date
        $user = User::where('email', $credentials['email'])->first();

        // Daca utilizatorul nu exista sau parola este incorecta
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid username or password.']);
        }

        // Verificam daca contul este activ sau nu este expirat
        if ($user->is_active === false || ($user->date_end && now()->greaterThan($user->date_end))) {
            return back()->withErrors(['email' => 'Expired or inactive account.']);
        }

        // Autentificare utilizator
        Auth::login($user);

        // Redirectionare catre pagina principala
        return redirect('/');
    }

    /**
     * Delogare autentificator curent si redirectionare catre pagina de logina.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse Redirect catre formularul de login.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
