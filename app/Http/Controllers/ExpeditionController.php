<?php

namespace App\Http\Controllers;

use App\Enums\ExpeditionStatusEnum;
use App\Events\ExpeditionStatusChanged;
use App\Events\ExpeditionViewed;
use App\Exceptions\ExpeditionException;
use App\Http\Requests\StoreExpeditionRequest;
use App\Http\Requests\UpdateExpeditionRequest;
use App\Models\Expedition;
use App\Services\ExpeditionService;
use App\Services\LogService;
use App\Services\ProtocolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class ExpeditionController extends Controller
{
    public function __construct(
        private readonly ProtocolService $protocolService,
        private readonly ExpeditionService $expeditionService,
        private readonly LogService $logService
    ) {}

    #[OA\Get(
        path: '/api/v1/expedition/{protocolId}',
        summary: 'Retorna informações das expedições através do protocolo',
        tags: ['Expedition'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Informações das expedições retornadas com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'expedition',
                            type: 'object',
                            example: [
                                'id' => 1,
                                'name' => 'Expedição Exemplo',
                                'description' => 'Descrição da Expedição Exemplo',
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado')
        ]
    )]
    public function get(string $protocolId, Request $request)
    {
        $this->expeditionService->validatePermission('get', $request->user(), Expedition::class);

        try {
            $expedition = $this->expeditionService->getByProtocol($protocolId);

            broadcast(new ExpeditionViewed($expedition)); //->toOthers();

            return $this->success(['expedition' => $expedition->load('status', 'kingdom', 'user', 'user.council')]);
        } catch (\Throwable $th) {
            $this->logService->logError('Error searching expedition: ' . $th->getMessage(), ['exception' => $th]);

            throw ExpeditionException::searchFailed();
        }
    }

    #[OA\Post(
        path: '/api/v1/expedition',
        summary: 'Cria uma nova expedição',
        tags: ['Expedition'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Expedição Exemplo'),
                        new OA\Property(property: 'start_date', type: 'string', example: '2023-01-01'),
                        new OA\Property(property: 'artifacts', type: 'string', example: 'Artefatos da Expedição'),
                        new OA\Property(property: 'note', type: 'string', example: 'Observações da Expedição'),

                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Expedição criada com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'expedition',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Expedição Exemplo',
                                    'start_date' => '2023-01-01',
                                    'artifacts' => 'Artefatos da Expedição',
                                    'note' => 'Observações da Expedição'
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
    public function store(StoreExpeditionRequest $storeExpeditionRequest)
    {
        $this->expeditionService->validatePermission('store', $storeExpeditionRequest->user(), Expedition::class);

        try {
            $request = $storeExpeditionRequest->validated();
            $request['kingdom_id'] = $storeExpeditionRequest->user()->kingdom_id;

            $protocol = DB::transaction(function () use ($request) {

                $expedition = $this->expeditionService->registerExpedition($request);
                return $this->protocolService->generateProtocol($expedition);
            });

            return $this->success(['protocol' => $protocol->uuid]);
        } catch (\Exception $e) {
            $this->logService->logError('Error registering expedition: ' . $e->getMessage(), ['exception' => $e]);

            throw ExpeditionException::registerFailed();
        }
    }

        #[OA\Put(
        path: '/api/v1/expedition/{protocolId}',
        summary: 'Atualiza uma expedição existente',
        tags: ['Expedition'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'status_id', type: 'integer', example: 1),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Expedição atualizada com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'expedition',
                                type: 'object',
                                example: [
                                    'id' => 1,
                                    'name' => 'Expedição Exemplo',
                                    'start_date' => '2023-01-01',
                                    'artifacts' => 'Artefatos da Expedição',
                                    'note' => 'Observações da Expedição',
                                    'status' => [
                                        'id' => 1,
                                        'status' => 'APROVADA',
                                    ]
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
    public function update(string $protocolId, UpdateExpeditionRequest $updateExpeditionRequest)
    {
        $this->expeditionService->validatePermission('updateStatus', $updateExpeditionRequest->user(), Expedition::class);

        $request = $updateExpeditionRequest->validated();
        $expedition = $this->expeditionService->getByProtocol($protocolId);

        if (!empty($request['status_id'])) {

            $updatedExpedition = DB::transaction(function () use ($expedition, $request, $updateExpeditionRequest) {

                if (in_array($expedition->status->slug, [
                    ExpeditionStatusEnum::rejeitada->value,
                    ExpeditionStatusEnum::autorizada->value
                ])) {

                    throw ExpeditionException::expeditionUpdatedAlready("Expedição já foi atualizada pelo Conselho para o status {$expedition->status->slug}.");
                }

                $this->expeditionService->validateStatusUpdate((int) $request['status_id'], $updateExpeditionRequest);

                $expedition->update([
                    'user_id' => $updateExpeditionRequest->user()->id,
                    'status_id' => $updateExpeditionRequest->status_id,
                    'rejection_reason' => $updateExpeditionRequest->rejection_reason ?? null
                ]);

                return $expedition->fresh();
            });

            broadcast(new ExpeditionStatusChanged($updatedExpedition)); //->toOthers();
        }
        return $this->success(['expedition' => $updatedExpedition]);
    }
}
