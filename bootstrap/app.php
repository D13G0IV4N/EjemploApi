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
        // Aquí configuramos los middleware como el de CSRF
        $middleware->validateCsrfTokens(
            except: [
                'stripe/*',
                'http://localhost:8000/api/registro',
                'http://localhost:8000/api/acceso',
                'http://localhost:8000/api/carros',
                'http://localhost:8000/api/carros/*'

            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Aquí puedes manejar excepciones personalizadas si lo deseas
    })
    ->create();
