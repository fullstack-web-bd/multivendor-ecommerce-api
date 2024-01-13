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

    public function errorResponse(string $message, Exception $exception, $data = null): \Illuminate\Http\JsonResponse
    {
        Log::error($message);
        Log::error($exception);

        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ]);
    }
}