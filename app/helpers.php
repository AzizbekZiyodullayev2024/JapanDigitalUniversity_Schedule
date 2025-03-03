<?php

if (!function_exists('apiResponse')) {
    function apiResponse($message, $status = 200, $data = [], $token = null)
    {
        $response = [
            'message' => $message,
            'data' => $data
        ];

        if ($token) {
            $response['token'] = $token;
        }
        return response()->json($response, $status);
    }
}