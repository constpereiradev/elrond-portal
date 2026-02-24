<?php

namespace App\Http\Controllers;

use App\Exceptions\ExpeditionStatusException;
use App\Http\Requests\StoreExpeditionStatusRequest;
use App\Models\ExpeditionStatus;
use App\Services\ExpeditionStatusService;
use App\Services\LogService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items as OAItems;

class ExpeditionStatusController extends Controller
{
    public function __construct(
        private readonly LogService $logService,
        private readonly ExpeditionStatusService $expeditionStatusService
    ) {}

    #[OA\Get(
        path: '/api/v1/expedition-status',
        summary: 'Retorna informações dos status de expedições',
        tags: ['Expedition Status'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações dos status de expedições retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'expedition_statuses',
                            type: 'array',
                            items: new OAItems(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Status Exemplo'),
                                    new OA\Property(property: 'description', type: 'string', example: 'Descrição do Status Exemplo'),
                                    new OA\Property(property: 'slug', type: 'string', example: 'status-exemplo')
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
        $expeditionStatus = ExpeditionStatus::all();
        return $this->success(['expedition_statuses' => $expeditionStatus]);
    }

    #[OA\Post(
        path: '/api/v1/expedition-status',
        summary: 'Cria um novo status de expedição',
        tags: ['Expedition Status'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Status Exemplo'),
                        new OA\Property(property: 'description', type: 'string', example: 'Descrição do Status Exemplo'),
                        new OA\Property(property: 'slug', type: 'string', example: 'status-exemplo'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Status de expedição criado com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'expedition_status',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Status Exemplo',
                                    'description' => 'Descrição do Status Exemplo',
                                    'slug' => 'status-exemplo'
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
    public function store(StoreExpeditionStatusRequest $request)
    {
        $request = $request->validated();

        try {
            $status = $this->expeditionStatusService->register($request);

            return $this->success(['expedition_status' => $status]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create expedition status', ['error' => $e->getMessage()]);
            throw ExpeditionStatusException::registerFailed();
        }
    }
}
