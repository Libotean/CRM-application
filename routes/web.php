<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsilierController;
use App\Http\Controllers\VehicleController;

// ruta pentru afisarea formularului login
Route::get('/login', [AuthController::class, 'showLoginForm']) -> name('login');

// ruta pentru a procesa trimiterea formularului login
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/', function (){
        $user = Auth::user();
        return view('dashboard', compact('user'));
    });

    // ruta pentru logout
    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    // RUTELE PENTRU VEHICULE

    // Lista de vehicule
    Route::get('/vehicule', [VehicleController::class, 'index']) -> name('vehicles.index');

    // Vanzare / Asignare client
    Route::get('/vehicule/{id}/vinde', [VehicleController::class, 'sell']) -> name('vehicles.sell');
    Route::post('/vehicule/{id}/vinde', [VehicleController::class, 'processSale']) -> name('vehicles.processSale');
    // =========================================================


    // grup rute admin
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    // RUTELE PENTRU CONSILIER
    Route::prefix('consilier')->middleware('is_consilier')->group(function () {

        // 1. Ruta de index (Tabelul Clienti)
        // O numim EXPLICIT 'admin.clients.index' ca să meargă butonul colegilor
        Route::get('index', [ConsilierController::class, 'index'])->name('admin.clients.index');

        // 2. Ruta de salvare (Adaugare client)
        // Pe asta o lasam cu nume de consilier
        Route::post('index', [ConsilierController::class, 'store'])->name('consilier.store');
    });

});
