<?php

namespace App\Support;
class ApiResponse
{
    public static function successResponse($data, $code = 200, $message = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    public static function errorResponse(string $type, $message = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'error' => [
                'type' => $type,
                'message' => $message
            ],
        ], $code);
    }
}
