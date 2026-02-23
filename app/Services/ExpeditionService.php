<?php

namespace App\Services;

use App\Enums\ExpeditionStatusEnum;
use App\Exceptions\ExpeditionException;
use App\Exceptions\ProtocolException;
use App\Exceptions\RequestException;
use App\Http\Requests\StoreExpeditionRequest;
use App\Models\Expedition;
use App\Models\ExpeditionProtocol;
use App\Models\ExpeditionStatus;
use App\Services\Abstract\BaseService;

class ExpeditionService extends BaseService
{
    public function getByProtocol(string $protocolId): mixed
    {
        $protocol = ExpeditionProtocol::where('uuid', $protocolId)->first();

        if (!$protocol) {
            throw ProtocolException::notFound();
        }

        $expedition = Expedition::find($protocol->expedition_id);

        if (!$expedition) {
            throw ExpeditionException::notFound();
        }

        return $expedition;
    }

    public function registerExpedition(StoreExpeditionRequest $request): Expedition
    {
        return Expedition::create([
            'kingdom_id' => $request->kingdom_id,
            'start_date' => $request->start_date,
            'status_id' => ExpeditionStatus::where('slug', 'analise')->first()->id,
            'artifacts' => $request->artifacts ?? null,
            'note' => $request->note ?? null
        ]);
    }

    public function validateStatusUpdate(int $statusId)
    {
        $expeditionStatus = ExpeditionStatus::find($statusId)->slug;

        if (!$expeditionStatus) {
            throw ExpeditionException::updateFailed('Status de expedição inválido.');
        }

        if ($expeditionStatus == ExpeditionStatusEnum::rejeitada->value) {

            if (empty($request->rejection_reason)) {
                throw RequestException::missingFields();
            }
        }
    }
}
