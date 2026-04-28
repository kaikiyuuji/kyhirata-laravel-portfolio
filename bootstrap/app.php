<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
        ]);

        $middleware->redirectTo(
            guests: '/',
            users: '/admin/dashboard',
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redireciona qualquer rota não encontrada (404) para a Home
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return redirect('/');
        });

        // Redireciona qualquer acesso negado (403) para a Home
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect('/');
        });
    })->create();
