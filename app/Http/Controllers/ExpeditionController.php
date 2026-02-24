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

class ExpeditionController extends Controller
{
    public function __construct(
        private readonly ProtocolService $protocolService,
        private readonly ExpeditionService $expeditionService,
        private readonly LogService $logService
    ) {}

    public function get(string $protocolId, Request $request)
    {
        $this->expeditionService->validatePermission('get', $request->user(), Expedition::class);

        try {
            $expedition = $this->expeditionService->getByProtocol($protocolId);

            broadcast(new ExpeditionViewed($expedition));//->toOthers();

            return $this->success(['expedition' => $expedition->load('status', 'kingdom', 'user', 'user.council')]);
        } catch (\Throwable $th) {
            $this->logService->logError('Error searching expedition: ' . $th->getMessage(), ['exception' => $th]);

            throw ExpeditionException::searchFailed();
        }
    }

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

            broadcast(new ExpeditionStatusChanged($updatedExpedition));//->toOthers();
        }
        return $this->success(['expedition' => $updatedExpedition]);
    }
}
