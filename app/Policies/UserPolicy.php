<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can create admin models.
     */
    public function storeAdmin(User $user): bool
    {
        return strtoupper($user->role->slug) == RoleEnum::admin;
    }
}
