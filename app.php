<?php

use App\Http\Controllers\AuthController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
        web: __DIR__.'/../routes/web.php',
        $middleware->alias([
            'auth' => Authenticate::class,
            'is_admin' => \App\Http\Middleware\AdminRoleCheck::class,
            'is_consilier' => \App\Http\Middleware\ConsilierRoleCheck::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
