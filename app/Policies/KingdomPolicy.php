<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class KingdomPolicy
{   
    /**
     * Apenas administradores podem criar reinos.
     * @param $user Usuário logado.
     * @return bool
     */
    public function store(User $user): bool
    {
        return $user->role->slug == RoleEnum::admin->value;
    }
}
