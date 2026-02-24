<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleException;
use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use App\Services\LogService;
use App\Services\RoleService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items as OAItems;

class RoleController extends Controller
{
    public function __construct(
        private readonly LogService $logService,
        private readonly RoleService $roleService
    ) {}

    #[OA\Get(
        path: '/api/v1/role',
        summary: 'Retorna informações dos papéis',
        tags: ['Role'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações dos papéis retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'roles',
                            type: 'array',
                            items: new OAItems(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Papel Exemplo'),
                                    new OA\Property(property: 'description', type: 'string', example: 'Descrição do Papel Exemplo')
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado')
        ]
    )]
    public function index()
    {
        $roles = Role::all();
        return $this->success(['roles' => $roles]);
    }

    #[OA\Post(
        path: '/api/v1/role',
        summary: 'Cria um novo papel',
        tags: ['Role'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Papel Exemplo'),
                        new OA\Property(property: 'description', type: 'string', example: 'Descrição do Papel Exemplo'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Papel criado com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'role',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Papel Exemplo',
                                    'description' => 'Descrição do Papel Exemplo',
                                ]
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Não autenticado'
            )
        ]
    )]
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
