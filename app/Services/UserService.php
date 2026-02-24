<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\Abstract\BaseService;

class UserService extends BaseService
{
    public function findById(int $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw UserException::notFound();
        }

        return $user;
    }

    public function store(array $request): User
    {
        return User::create([
            "name" => $request['name'],
            "email" => $request['email'],
            "password" => bcrypt($request['password']),
            'role_id' => $request['role_id'],
            'kingdom_id' => $request['kingdom_id'] ?? null,
            'council_id' => $request['council_id'] ?? null,
        ]);
    }

    public function validateUserStatus(User $user): void
    {
        if (!$user->isActive()) {
            throw UserException::inactiveUser();
        }
    }
}
