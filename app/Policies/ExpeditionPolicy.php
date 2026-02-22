<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class ExpeditionPolicy
{
    /**
     * Apenas Conselhos ativos e reinos podem visualizar uma expedição.
     * @param $user User
     * @return bool Retorno
     */
    public function get(User $user): bool
    {
        return in_array($user->type(), [RoleEnum::conselho->value,  RoleEnum::reino->value]) && $user->isActive();
    }

    /**
     * Apenas reinos ativos podem registrar uma nova expedição.
     * @param $user User
     * @return bool Retorno
     */
    public function store(User $user): bool
    {
        return $user->type() == RoleEnum::reino->value && $user->isActive();
    }

    /**
     * Apenas Conselhos ativos podem atualizar o status de uma expedição.
     * @param $user User
     * @return bool Retorno
     */
    public function updateStatus(User $user): bool
    {
        return $user->type() == RoleEnum::conselho->value && $user->isActive();
    }
}
