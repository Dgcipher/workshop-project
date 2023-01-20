<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function apiResponse($status, $statusCode, $message, $data)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
