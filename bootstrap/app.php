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
        $middleware->alias([
            'auth'                 => \App\Http\Middleware\Authenticate::class,
'institution.user'      => \App\Http\Middleware\EnsureInstitutionUser::class,
            'active.institution'    => \App\Http\Middleware\EnsureActiveInstitution::class,
            'admin.user'            => \App\Http\Middleware\EnsureAdminUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
