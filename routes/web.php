<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
    });

    // ruta pentru logout
    Route::post('/logout', [AuthController::class, 'logout']) -> name('logout');

    // grup rute admin
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {

        // lista utilizatori
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // form adaugare utilizator
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

        // procesare si salvare utilizator
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    
    //routa pentru consilier
    Route::prefix('consilier')->middleware('is_consilier')->name('consilier.')->group(function () {
        Route::get('/', [ConsilierController::class, 'index'])->name('index');

        Route::post('/', [ConsilierController::class, 'store'])->name('store');
    });

});
