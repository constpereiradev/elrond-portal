<?php

namespace App\Services\Abstract;

use Illuminate\Auth\Access\AuthorizationException;

abstract class BaseService
{
    public function validatePermission(string $action, $user, $model): void
    {
        if (!$user->can($action, $model)) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }
}
