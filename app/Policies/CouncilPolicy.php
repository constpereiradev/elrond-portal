<?php

namespace App\Policies;

use App\Models\Council;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Role;

class CouncilPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        $role = Role::find($user->role_id);
        return strtoupper($role->slug) === RoleEnum::admin->value;
    }
}
