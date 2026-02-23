<?php

namespace App\Services;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use App\Services\Abstract\BaseService;

class RoleService extends BaseService
{
    public function store(StoreRoleRequest $request): Role
    {
        return Role::create([
            'name' => $request->name,
            'slug' => strtoupper($request->slug),
        ]);
    }
}
