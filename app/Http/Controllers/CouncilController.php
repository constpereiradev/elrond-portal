<?php

namespace App\Http\Controllers;

use App\Exceptions\CouncilException;
use App\Http\Requests\StoreCouncilRequest;
use App\Models\Council;
use App\Services\CouncilService;
use App\Services\LogService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items as OAItems;

class CouncilController extends Controller
{
    public function __construct(
        private readonly CouncilService $councilService,
        private readonly LogService $logService
    ) {}

    #[OA\Get(
        path: '/api/v1/council',
        summary: 'Retorna informações dos conselhos',
        tags: ['Council'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações dos conselhos retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'councils',
                            type: 'array',
                            items: new OAItems(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Conselho Exemplo'),
                                    new OA\Property(property: 'description', type: 'string', example: 'Descrição do Conselho Exemplo'),
                                    new OA\Property(property: 'status', type: 'string', example: 'a'),
                                    new OA\Property(property: 'created_at', type: 'string', example: '2023-01-01 00:00:00'),
                                    new OA\Property(property: 'updated_at', type: 'string', example: '2023-01-01 00:00:00')


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
        $councils = Council::all();
        return $this->success(['councils' => $councils]);
    }

    #[OA\Post(
        path: '/api/v1/council',
        summary: 'Cria um novo conselho',
        tags: ['Council'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Conselho Exemplo'),
                        new OA\Property(property: 'description', type: 'string', example: 'Descrição do Conselho Exemplo'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Conselho criado com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'council',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Conselho Exemplo',
                                    'description' => 'Descrição do Conselho Exemplo',
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
    public function store(StoreCouncilRequest $request)
    {
        $this->councilService->validatePermission('store', $request->user(), Council::class);
        $request = $request->validated();

        try {
            $council = $this->councilService->store($request);

            return $this->success(['council' => $council]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create council', ['error' => $e->getMessage()]);
            throw CouncilException::registerFailed();
        }
    }
}
