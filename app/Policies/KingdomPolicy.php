<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Role;

class KingdomPolicy
{   
    /**
     * Apenas administradores podem criar reinos.
     * @param $user Usuário logado.
     * @return bool
     */
    public function store(User $user): bool
    {
        $role = Role::find($user->role_id);
        return $role?->slug == RoleEnum::admin->value;
    }
}
