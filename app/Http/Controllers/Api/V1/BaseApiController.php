<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function success($data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 'success', // backward compatible with existing mobile clients
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message, $errors = [], int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
