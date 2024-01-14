<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait Responsable
{
    public function successResponse(string $message, mixed $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function errorResponse(string $message, $exception, $data = null): \Illuminate\Http\JsonResponse
    {
        Log::error($message);
        Log::error($exception);

        if (config('app.debug')) {
            $message = $exception->getMessage();
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ]);
    }
}