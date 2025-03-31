<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function ResponseJson(bool $success, mixed $data = null, string $message = '', $pagination = null, $statusCode = null): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination,
        ], $statusCode ?? ($success ? 200 : 400));
    }
}
