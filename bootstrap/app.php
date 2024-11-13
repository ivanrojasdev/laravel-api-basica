<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'error' => 'The specified resource could not be found.',
                'message' => $e->getMessage(),
                'status' => 404,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
            ], 404);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => $e->getMessage(),
                'status' => 404,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
            ], 404);
        });

        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => $e->getMessage(),
                'status' => 401,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
            ], 401);
        });

        $exceptions->renderable(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => $e->getMessage(),
                'status' => 403,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
            ], 403);
        });

        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->getMessage(),
                'status' => 422,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
                'messages' => $e->errors()
            ], 422);
        });

        $exceptions->renderable(function (Throwable $e, Request $request) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'status' => 500,
                'path' => $request->path(),
                'timestamp' => now()->format('d-m-Y H:i:s'),
            ], 500);
        });
    })->create();
