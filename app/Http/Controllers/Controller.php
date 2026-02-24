<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Elrond Portal API", version: "1.0.0")]
#[OA\Server(url: "https://elrond-portal.test")]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    in: 'header',
    name: 'Authorization'
)]
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
