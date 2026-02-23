<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProtocolException extends Exception
{
    public function __construct(string $message, int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }

    public static function notFound(?string $message = null): self
    {
        return new self($message ?? 'Protocolo não encontrado.', Response::HTTP_NOT_FOUND);
    }
    
}