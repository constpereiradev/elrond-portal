<?php

namespace App\Exceptions;

use App\Exceptions\Interface\ExceptionInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class KingdomException extends Exception implements ExceptionInterface
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

    public static function registerFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao registrar reino.', Response::HTTP_BAD_REQUEST);
    }
}
