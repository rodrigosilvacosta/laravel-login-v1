<?php

use App\Domain\Shared\Exceptions\AppDomainException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AppDomainException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });
    })->create();
