<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // check if users exists
        $user = User::where('email', $credentials['email'])->first();

        if(!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid username or password.']);
        }

        if($user->is_active === false || ($user->date_end && now()->greaterThan($user->date_end))) {
            return back()->withErrors(['email' => 'Expired or inactive account.']);
        }

        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect('/login');
    }
}
