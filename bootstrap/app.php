<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhook/fedapay',
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Page 500 propre et identique pour tous : aucun détail d'erreur affiché.
        // La trace complète reste dans storage/logs/laravel.log.
        // On laisse passer les exceptions qui ont leur propre gestion intégrée :
        //   - AuthenticationException (visiteur non connecté) -> redirection vers /login
        //   - ValidationException (formulaire invalide) -> retour inline des erreurs (422)
        //   - Erreurs HTTP < 500 (404, 403, 419) -> pages d'erreur Laravel par défaut
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                if ($status < 500) {
                    return null;
                }

                return response()->view('errors.500', [], $status);
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException
                || $e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }

            return response()->view('errors.500', [], 500);
        });
    })->create();
