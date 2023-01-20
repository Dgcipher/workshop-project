<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function success($status, $statusCode, $message = '', $data = [])
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function error($status, $statusCode, $message = '', $data = [])
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
