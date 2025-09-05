<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => null,
            'errors' => null
        ], $statusCode);
    }

    /**
     * Error response
     */
    protected function errorResponse(string $message = 'Error', $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => null,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Paginated response
     */
    protected function paginatedResponse($data, $pagination = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $data,
            'meta' => [
                'pagination' => $pagination
            ],
            'errors' => null
        ]);
    }
}
