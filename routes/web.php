<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsilierController;
use App\Http\Controllers\VehicleController;

// 1. Rute Login
Route::get('/login', [AuthController::class, 'showLoginForm']) -> name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. Grupul Protejat
Route::middleware('auth')->group(function () {

    // ✅ REPARAT EROAREA [dashboard]
    // Am adăugat ->name('dashboard')
    Route::get('/', function (){
        $user = Auth::user();
        return view('dashboard', compact('user'));
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    // --- RUTE VEHICULE ---
    Route::get('/vehicule', [VehicleController::class, 'index']) -> name('vehicles.index');
    Route::get('/vehicule/{id}/vinde', [VehicleController::class, 'sell']) -> name('vehicles.sell');
    Route::post('/vehicule/{id}/vinde', [VehicleController::class, 'processSale']) -> name('vehicles.processSale');

    // Rute noi pentru editare (pregătire pentru viitor, nu strică nimic dacă stau aici)
    Route::get('/vehicule/{id}/edit', [VehicleController::class, 'edit']) -> name('vehicles.edit');
    Route::put('/vehicule/{id}', [VehicleController::class, 'update']) -> name('vehicles.update');


    // --- GRUP ADMIN ---
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {

        // Lista utilizatori
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // Formular adaugare
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        // ✅ REPARAT EROAREA [admin.users.show]
        // Colegii tăi au un buton de "Vezi Detalii User" care caută ruta asta.
        // O punem să ducă tot la index momentan, sau la o metodă 'show' dacă există.
        // Dacă primești eroare că "Method show does not exist", schimbă 'show' cu 'index' mai jos.
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    });


    // --- GRUP CONSILIER ---
    Route::prefix('consilier')->middleware('is_consilier')->group(function () {

        // ✅ REPARAT EROAREA [admin.clients.index]
        // Butonul de "Înapoi" caută exact numele ăsta.
        Route::get('index', [ConsilierController::class, 'index'])->name('admin.clients.index');

        Route::post('index', [ConsilierController::class, 'store'])->name('consilier.store');
    });

});
