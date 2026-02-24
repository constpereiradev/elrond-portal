<?php

namespace App\Exceptions;

use App\Exceptions\Interface\ExceptionInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RequestException extends Exception implements ExceptionInterface
{
    public function __construct(string $message, int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->getCode() ?: 403);
    }

    public static function missingFields(?string $message = null): self
    {
        return new self($message ?? 'Campos obrigatórios faltando.', Response::HTTP_BAD_REQUEST);
    }

}
