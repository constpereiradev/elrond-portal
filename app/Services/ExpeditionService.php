<?php

namespace App\Services;

use App\Enums\ExpeditionStatusEnum;
use App\Exceptions\ExpeditionException;
use App\Exceptions\ProtocolException;
use App\Exceptions\RequestException;
use App\Http\Requests\StoreExpeditionRequest;
use App\Http\Requests\UpdateExpeditionRequest;
use App\Models\Expedition;
use App\Models\ExpeditionProtocol;
use App\Models\ExpeditionStatus;
use App\Services\Abstract\BaseService;
use Symfony\Component\HttpFoundation\Request;

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

    public function registerExpedition(array $request): Expedition
    {
        return Expedition::create([
            'kingdom_id' => $request['kingdom_id'],
            'start_date' => $request['start_date'],
            'status_id' => ExpeditionStatus::where('slug', ExpeditionStatusEnum::analise->value)->first()->id,
            'artifacts' => $request['artifacts'] ?? null,
            'note' => $request['note'] ?? null
        ]);
    }

    public function validateStatusUpdate(int $statusId, UpdateExpeditionRequest $request): void
    {
        $expeditionStatus = ExpeditionStatus::find($statusId);

        if (!$expeditionStatus) {
            throw ExpeditionException::updateFailed('Status de expedição inválido.');
        }

        if ($expeditionStatus->slug == ExpeditionStatusEnum::rejeitada->value) {

            if (empty($request->rejection_reason)) {
                throw RequestException::missingFields();
            }
        }
    }
}
