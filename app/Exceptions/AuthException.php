<?php

namespace App\Exceptions;

use App\Exceptions\Interface\ExceptionInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthException extends Exception implements ExceptionInterface
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

    public static function invalidCredentials(?string $message = null): self
    {
        return new self($message ?? 'As credenciais fornecidas não correspondem aos nossos registros.', Response::HTTP_UNAUTHORIZED);
    }

    public static function userNotFound(?string $message = null): self
    {
        return new self($message ?? 'Usuário não encontrado.', Response::HTTP_NOT_FOUND);
    }

    public static function tokenGenerationFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao gerar o token de autenticação.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function invalidToken(?string $message = null): self
    {
        return new self($message ?? 'Token de autenticação inválido.', Response::HTTP_UNAUTHORIZED);
    }

    public static function logoutFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao realizar logout.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function authFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao realizar autenticação.', Response::HTTP_UNAUTHORIZED);
    }
}