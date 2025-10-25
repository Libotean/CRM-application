<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    $user = Auth::user();
    return view('welcome', compact('user'));
})->middleware('auth');

// ruta pentru afisarea formularului login
Route::get('/login', [AuthController::class, 'showLoginForm']) -> name('login');

// ruta pentru a procesa trimiterea formularului login
Route::post('/login', [AuthController::class, 'login']);

// ruta pentru logout
Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

