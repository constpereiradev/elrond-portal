<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Exceptions\UserException;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\LogService;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function store(StoreUserRequest $storeUserRequest)
    {
        $request = $storeUserRequest->validated();

        if (!empty($request['council_id']) && !empty($request['kingdom_id'])) {
            throw UserException::invalidAssociation();
        }

        if (!empty($request['kingdom_id'])) {
            $request['role_id'] = Role::where('slug', RoleEnum::reino->value)->first()->id;
        }

        if (!empty($request['council_id'])) {
            $request['role_id'] = Role::where('slug', RoleEnum::conselho->value)->first()->id;
        }

        try {
            $role = Role::find($request['role_id']);
            if ($role->slug == RoleEnum::admin->value) {
                $this->userService->validatePermission('storeAdmin', $storeUserRequest->user(), User::class);
            }

            $user = $this->userService->store($request);

            return $this->success(['user' => $user]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create user', ['error' => $e->getMessage()]);

            throw UserException::registerFailed();
        }
    }
}
