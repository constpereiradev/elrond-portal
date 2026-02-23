<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller
{
    public function success(?array $params = [], ?string $message = "Sucess",): JsonResponse
    {
        $message = ['message' => $message];
        $sucessParams = array_merge($message, $params);

        return response()->json($sucessParams, 200);
    }

    public function error(?array $params = [], ?string $message = "Error"): JsonResponse
    {
        $message = ['message' => $message];
        $sucessParams = array_merge($message, $params);

        return response()->json($sucessParams);
    }
}
