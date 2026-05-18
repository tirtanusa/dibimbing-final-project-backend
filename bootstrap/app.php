<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            if($request->is('api/*')){
                return $this->unauthorizedResponse('Anda tidak memiliki akses untuk mengubah data ini');
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if($request->is('api/*')){
                return $this->notFoundResponse('API Route Not Found');
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if($request->is('api/*')){
                return $this->notLoggedInResponse('Anda belum login');
            }
        });
    })->create();
