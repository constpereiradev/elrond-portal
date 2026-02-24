<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can create admin models.
     */
    public function storeAdmin(User $user): bool
    {
        $role = Role::find($user->role_id);
        return strtoupper($role->slug) == RoleEnum::admin->value;
    }
}
