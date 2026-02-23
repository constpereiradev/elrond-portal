<?php

namespace App\Policies;

use App\Models\Council;
use App\Models\User;
use App\Enums\RoleEnum;

class CouncilPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return strtoupper($user->role->slug) == RoleEnum::admin;
    }
}
