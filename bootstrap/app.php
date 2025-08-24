<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ALIAS MIDDLEWARE (WAJIB)
        $middleware->alias([
            // pakai Authenticate milik App (agar redirectTo ke /<prefix>/login jalan)
            'auth'     => \App\Http\Middleware\Authenticate::class,
            // override 'guest' supaya /admin/login tidak mental ke home
            'guest'    => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // cek role admin
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // (opsional) $middleware->web([...]);
        // (opsional) $middleware->api([...]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
