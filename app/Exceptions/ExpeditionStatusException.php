<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ExpeditionStatusException extends Exception
{
    public function __construct(string $message, int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }
   
    public static function registerFailed(?string $message = null): self
    {
        return new self($message ?? 'Falha ao registrar status de expedição.', Response::HTTP_BAD_REQUEST);
    }
}
