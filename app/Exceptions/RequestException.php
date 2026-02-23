<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class RequestException extends Exception
{
    public function __construct(string $message, int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }

    public static function missingFields(?string $message = null): self
    {
        return new self($message ?? 'Campos obrigatórios faltando.', Response::HTTP_BAD_REQUEST);
    }

}
