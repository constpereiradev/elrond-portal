<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class KingdomPolicy
{
    public function store(User $user): bool
    {
        return $user->role->slug == RoleEnum::admin;
    }

    public function update(User $user): bool
    {
        return $user->role->slug == RoleEnum::admin;
    }
}
