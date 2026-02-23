<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleException;
use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use App\Services\LogService;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(
        private readonly LogService $logService,
        private readonly RoleService $roleService
    ) {}

    public function index()
    {
        $roles = Role::all();
        return $this->success(['roles' => $roles]);
    }

    public function store(StoreRoleRequest $request)
    {
        $request = $request->validated();

        try {
            $role = $this->roleService->store($request);
            return $this->success(['role' => $role]);

        } catch (\Exception $e) {
            $this->logService->logError('Failed to create role', ['error' => $e->getMessage()]);

            throw RoleException::registerFailed();
        }
    }
}
