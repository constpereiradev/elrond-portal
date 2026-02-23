<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserException extends Exception
{
    public function __construct(string $message, int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }

    public static function notFound(?string $message = null): self
    {
        return new self($message ?? 'Usuário não encontrado.', Response::HTTP_NOT_FOUND);
    }

    public static function registerFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao registrar usuário.', Response::HTTP_BAD_REQUEST);
    }

    public static function inactiveUser(?string $message = null): self
    {
        return new self($message ?? 'Usuário inativo.', Response::HTTP_FORBIDDEN);
    }

    public static function invalidAssociation(?string $message = null): self
    {
        return new self($message ?? 'Usuário não pode pertencer a ambos reino e conselho.', Response::HTTP_FORBIDDEN);
    }
}
