<?php

namespace App\Services\Abstract;


abstract class BaseService
{
    public function validatePermission(string $action, $user, $model): void
    {
        if (!$user->can($action, $model)) {
            abort(403, 'Unauthorized action.');
        }
    }
}