<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Send a success response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function successResponse(
        mixed $data,
        string $message = 'Operation successful',
        int $code = 200
    ): JsonResponse {

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'errors' => [],
            'statusCode' => $code,
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|null  $errors
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'An error occurred',
        int $code = 400,
        array $errors = null
    ): JsonResponse {

        return response()->json([
            'status' => 'error',
            'message' => $errors ? 'Validation errors occurred' : $message,
            'data' => [],
            'errors' => $errors,
            'statusCode' => $code,
        ], $code);
    }
}
