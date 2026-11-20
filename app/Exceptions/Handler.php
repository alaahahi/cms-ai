<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->shouldReturnApiJson($request)) {
            return $this->renderApiException($request, $exception);
        }

        if ($exception instanceof AuthenticationException) {
            return redirect()->guest(route('login'));
        }

        return parent::render($request, $exception);
    }

    protected function shouldReturnApiJson($request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }

    protected function renderApiException($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Unauthenticated',
                'errors' => [],
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Resource not found',
                'errors' => [],
            ], 404);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $exception->getMessage() ?: 'HTTP error',
                'errors' => [],
            ], $exception->getStatusCode());
        }

        if (config('app.debug')) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $exception->getMessage(),
                'errors' => [
                    'exception' => class_basename($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ], 500);
        }

        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Server error',
            'errors' => [],
        ], 500);
    }
}
