<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ConsilierController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TestDriveController;

// Rute Login
Route::get('/login', [AuthController::class, 'showLoginForm']) -> name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', function (){
        $user = Auth::user();
        return view('dashboard', compact('user'));
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    // =========================================================
    // ✅ RUTELE PENTRU VEHICULE (Parc Auto & Vânzare)
    // =========================================================

    // 1. Lista de vehicule
    Route::get('/vehicule', [VehicleController::class, 'index'])->name('vehicles.index');

    // 2. Fluxul de Vânzare (Asignare Client)
    Route::get('/vehicule/{id}/vinde', [VehicleController::class, 'sell'])->name('vehicles.sell');
    Route::post('/vehicule/{id}/vinde', [VehicleController::class, 'processSale'])->name('vehicles.processSale');

    // 3. Editare Vehicul (Opțional)
    Route::get('/vehicule/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicule/{id}', [VehicleController::class, 'update'])->name('vehicles.update');

    // =========================================================
    // ✅ RUTA PENTRU TEST DRIVE (FR-16)
    // Aceasta este ruta pe care o caută formularul din show.blade.php
    // =========================================================
    Route::post('/clients/{client}/test-drive', [TestDriveController::class, 'store'])->name('test_drives.store');


    // --- GRUP RUTE ADMIN ---
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('clients', \App\Http\Controllers\AdminClientController::class);

        // Rute manuale
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // --- GRUP RUTE CONSILIER ---
    Route::prefix('consilier')->middleware('is_consilier')->name('consilier.')->group(function () {

        Route::get('/clients/index', [ConsilierController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ConsilierController::class, 'create'])->name('clients.create');
        Route::post('/clients/store', [ConsilierController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}', [ConsilierController::class, 'show'])->name('clients.show');
        Route::get('/clients/{client}/edit', [ConsilierController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{client}/update', [ConsilierController::class, 'update'])->name('clients.update');

        // Rute pentru Leads / Email
        Route::post('/clients/{client}/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::patch('/leads/{lead}/toggle', [LeadController::class, 'toggleStatus'])->name('leads.toggle');
        Route::post('/clients/{client}/send-email', [LeadController::class, 'sendEmail'])->name('leads.sendEmail');
    });

});
