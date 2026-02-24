<?php

namespace App\Exceptions\Interface;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ExceptionInterface
{
    public function render($request): JsonResponse;
}