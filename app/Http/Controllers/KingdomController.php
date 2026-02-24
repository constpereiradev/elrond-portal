<?php

namespace App\Http\Controllers;

use App\Exceptions\KingdomException;
use App\Http\Requests\StoreKingdomRequest;
use App\Models\Kingdom;
use App\Services\KingdomService;
use App\Services\LogService;
use OpenApi\Attributes as OA;

class KingdomController extends Controller
{
    public function __construct(
        private readonly KingdomService $kingdomService,
        private readonly LogService $logService
    ) {}

    #[OA\Get(
        path: '/api/v1/kingdom',
        summary: 'Retorna informações dos reinos',
        tags: ['Kingdom'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações dos reinos retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'kingdoms',
                            type: 'array',
                            example: [
                                ['id' => 1, 'name' => 'Reino Exemplo', 'description' => 'Descrição do Reino Exemplo']
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado')
        ]
    )]
    public function index()
    {
        $kingdoms = Kingdom::all();
        return $this->success(['kingdoms' => $kingdoms]);
    }

    #[OA\Post(
        path: '/api/v1/kingdom',
        summary: 'Cria um novo reino',
        tags: ['Kingdom'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Reino Exemplo'),
                        new OA\Property(property: 'description', type: 'string', example: 'Descrição do Reino Exemplo'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reino criado com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'kingdom',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Reino Exemplo',
                                    'description' => 'Descrição do Reino Exemplo',
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
    public function store(StoreKingdomRequest $request)
    {
        $this->kingdomService->validatePermission('store', $request->user(), Kingdom::class);
        $request = $request->validated();

        try {
            $kingdom = $this->kingdomService->store($request);

            return $this->success(['Kingdom' => $kingdom]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create kingdom', ['error' => $e->getMessage()]);

            throw KingdomException::registerFailed();
        }
    }
}
