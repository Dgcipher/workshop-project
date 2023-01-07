<?php

namespace App\Methods;

use Illuminate\Http\JsonResponse;

trait Helpers
{
    // api return method
    final public function responseJson($status, $message, $data = null): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response);
    }

}
