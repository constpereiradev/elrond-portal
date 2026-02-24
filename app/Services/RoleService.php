<?php

namespace App\Services;

use App\Models\Role;
use App\Services\Abstract\BaseService;

class RoleService extends BaseService
{
    public function store(array $request): Role
    {
        return Role::create([
            'name' => $request['name'],
            'slug' => strtoupper($request['slug']),
        ]);
    }
}
