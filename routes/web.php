<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ConsilierController;

//Route::get('/', function () {
//    $user = Auth::user();
//    return view('dashboard', compact('user'));
//})->middleware('auth');

// ruta pentru afisarea formularului login
Route::get('/login', [AuthController::class, 'showLoginForm']) -> name('login');

// ruta pentru a procesa trimiterea formularului login
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/', function (){
       $user = Auth::user();
       return view('dashboard', compact('user'));
    })->name('dashboard');

    // ruta pentru logout
    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    // grup rute admin
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {

        Route::resource('users', UserController::class);

        Route::resource('clients', \App\Http\Controllers\AdminClientController::class);

        // lista utilizatori
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // form adaugare utilizator
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

        // procesare si salvare utilizator
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        // afisare detalii utilizator
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        // form editare utilizator
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        // form actualizare utilizator
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // stergere utilizator
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    
    //routa pentru consilier
    Route::prefix('consilier')->middleware('is_consilier')->name('consilier.')->group(function () {
        Route::get('/clients/index', [ConsilierController::class, 'index'])->name( 'clients.index');

        Route::get('/clients/create', [ConsilierController::class, 'create'])->name( 'clients.create');

        Route::post('/clients/store', [ConsilierController::class, 'store'])->name('clients.store');
    });

});
