<?php

namespace App\Exceptions;

use App\Exceptions\Interface\ExceptionInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExpeditionException extends Exception implements ExceptionInterface
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

    public static function notFound(?string $message = null): self
    {
        return new self($message ?? 'Expedição não encontrada.', Response::HTTP_NOT_FOUND);
    }

    public static function searchFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao buscar expedição.', Response::HTTP_BAD_REQUEST);
    }

    public static function registerFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao registrar expedição.', Response::HTTP_BAD_REQUEST);
    }

    public static function updateFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao atualizar expedição.', Response::HTTP_BAD_REQUEST);
    }

    public static function expeditionUpdatedAlready(?string $message = null): self
    {
        return new self($message ?? 'Expedição já foi atualizada pelo Conselho', Response::HTTP_BAD_REQUEST);
    }
}
