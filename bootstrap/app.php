<?php

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyRole;
use Illuminate\Foundation\Application;
use App\Http\Middleware\UpdateLastActive;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verifyRole' => VerifyRole::class,
            'authorized' => EnsureUserIsActive::class,
            'touch' => UpdateLastActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($exception->getStatusCode() == 403) {
                return response()->view('errors.403', [], 403);
            }
        });
    })->create();
