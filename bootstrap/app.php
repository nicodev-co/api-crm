<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'title' => 'Validation Error',
                    'detail' => $e->errors(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            if ($e->getPrevious() instanceof ModelNotFoundException) {
                return response()->json([
                    'errors' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'title' => 'Resource not found',
                        'detail' => 'The requested '.str()->lower(class_basename($e->getPrevious()->getModel())).' does not exist',
                    ],
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'title' => 'Server Error',
                    'detail' => 'An unexpected error occurred. Please try again later.',
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
