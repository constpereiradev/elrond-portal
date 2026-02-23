<?php

namespace App\Http\Controllers;

use App\Enums\ExpeditionStatusEnum;
use App\Events\ExpeditionStatusChanged;
use App\Events\ExpeditionViewed;
use App\Exceptions\ExpeditionException;
use App\Http\Requests\StoreExpeditionRequest;
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

    public function store(StoreExpeditionRequest $request)
    {
        $this->expeditionService->validatePermission('store', $request->user(), Expedition::class);
        $request->merge([
            'kingdom_id' => $request->user()->kingdom_id,
        ]);

        try {
            $request = $request->validated();
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

    public function update(string $protocolId, Request $request)
    {
        $request = $request->validated();
        $expedition = $this->expeditionService->getByProtocol($protocolId);

        if (!empty($request->status_id)) {

            $expedition = DB::transaction(function () use ($expedition, $request) {

                $this->expeditionService->validatePermission('updateStatus', $request->user(), Expedition::class);

                if (in_array($expedition->status->slug, [
                    ExpeditionStatusEnum::rejeitada->value,
                    ExpeditionStatusEnum::autorizada->value
                ])) {

                    throw ExpeditionException::expeditionUpdatedAlready("Expedição já foi atualizada pelo Conselho para o status {$expedition->status->slug}.");
                }

                $this->expeditionService->validateStatusUpdate((int) $request->status_id, $request);

                $expedition->update([
                    'user_id' => $request->user()->id,
                    'status_id' => $request->status_id,
                    'rejection_reason' => $request->rejection_reason ?? null
                ]);

                return $expedition;
            });

            broadcast(new ExpeditionStatusChanged($expedition));//->toOthers();
        }
        return $this->success(['expedition' => $expedition]);
    }
}
