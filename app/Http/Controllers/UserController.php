<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Exceptions\UserException;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\LogService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LogService $logService
    ) {}

    public function get(Request $request)
    {
        return $this->success(['user' => $request->user()->load('role', 'council', 'kingdom')]);
    }

    public function store(StoreUserRequest $request)
    {
        $request = $request->validated();

        if (!empty($request->council_id) && !empty($request->kingdom_id)) {
            throw UserException::invalidAssociation();
        }

        if (!empty($request->kingdom_id)) {
            $request->merge([
                'role_id' => Role::where('slug', RoleEnum::reino->value)->first()->id,
            ]);
        }

        if (!empty($request->council_id)) {
            $request->merge([
                'role_id' => Role::where('slug', RoleEnum::conselho->value)->first()->id,
            ]);
        }

        try {
            $role = Role::find($request->role_id);
            if ($role->slug == RoleEnum::admin) {
                $this->userService->validatePermission('storeAdmin', $request->user(), User::class);
            }

            $user = $this->userService->store($request);

            return $this->success(['user' => $user]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create user', ['error' => $e->getMessage()]);

            throw UserException::registerFailed();
        }
    }
}
