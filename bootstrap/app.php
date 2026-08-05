<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias du middleware (garder pour pouvoir l'utiliser manuellement)
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
        
        // ❌ NE PAS ajouter au groupe web globalement
        // $middleware->prependToGroup('web', \App\Http\Middleware\IsAdmin::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();