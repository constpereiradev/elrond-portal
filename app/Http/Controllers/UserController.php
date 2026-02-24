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
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LogService $logService
    ) {}

    #[OA\Get(
        path: '/api/v1/auth/user',
        summary: 'Retorna informações do usuário logado',
        tags: ['User'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações do usuário retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            example: ['name' => 'John Doe', 'email' => 'john@example.com']
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado')
        ]
    )]
    public function get(Request $request)
    {
        return $this->success(['user' => $request->user()->load('role', 'council', 'kingdom')]);
    }

    #[OA\Post(
        path: '/api/v1/user',
        summary: 'Cria um novo usuário',
        tags: ['User'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                        new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário criado com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            example: ['name' => 'John Doe', 'email' => 'john@example.com']
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado')
        ]
    )]
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
