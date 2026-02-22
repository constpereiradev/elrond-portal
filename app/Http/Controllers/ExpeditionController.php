<?php

namespace App\Http\Controllers;

use App\Enums\ExpeditionStatusEnum;
use App\Models\Expedition;
use App\Models\ExpeditionProtocol;
use App\Models\ExpeditionStatus;
use App\Models\User;
use App\Services\ExpeditionService;
use App\Services\ProtocolService;
use Illuminate\Http\Request;

class ExpeditionController extends Controller
{
    public function __construct(
        private readonly ProtocolService $protocolService,
        private readonly ExpeditionService $expeditionService
    ) {}

    public function get(string $protocolId, Request $request)
    {
        if (!$request->user()->can('get', Expedition::class)) {
            abort(402);
        }

        $expedition = $this->expeditionService->getByProtocol($protocolId);

        return $this->success(['expedition' => $expedition->load('status', 'kingdom', 'user', 'user.council')]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->can('store', Expedition::class)) {
            abort(403);
        }

        $request->merge([
            'kingdom_id' => $request->user()->kingdom_id,
        ]);

        try {
            $request->validate([
                'start_date' => ['required', 'date'],
                'artifacts' => ['text'],
                'note' => ['text'],
            ]);

            $expedition = Expedition::create([
                'kingdom_id' => $request->kingdom_id,
                'start_date' => $request->start_date,
                'status_id' => ExpeditionStatus::where('slug', 'analise')->first()->id,
                'artifacts' => $request->artifacts ?? null,
                'note' => $request->note ?? null
            ]);

            //Deve ser gerado um protocolo e retornado.
            $protocol = $this->protocolService->generateProtocol($expedition);

            return $this->success(['protocol' => $protocol->uuid]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function update(string $protocolId, Request $request)
    {
        $request->validate([
            'status_id' => ['integer', 'exists:expedition_status,id']
        ]);

        $expedition = $this->expeditionService->getByProtocol($protocolId);

        if (!empty($request->status_id)) {
            if (!$request->user()->can('updateStatus', Expedition::class)) {
                abort(403);
            }


            if (in_array($expedition->status->slug, [
                ExpeditionStatusEnum::rejeitada->value,
                ExpeditionStatusEnum::autorizada->value
            ])) {
                return $this->error([], "Expedição já foi atualizada pelo Conselho para o status {$expedition->status->slug}.");
            }

            $expeditionStatus = ExpeditionStatus::find($request->status_id);

            if($expeditionStatus->slug == ExpeditionStatusEnum::rejeitada->value) {

                if(empty($request->rejection_reason)){
                    return $this->error(['fields' => ['rejection_reason']], 'Para rejeitar uma expedição, deve ser informado o motivo.*');
                }

                $expedition->rejection_reason = $request->rejection_reason;
            }

            $expedition->user_id = $request->user()->id;
            $expedition->status_id = $request->status_id;
        }

        $expedition->save();
        return $this->success(['expedition' => $expedition]);
    }
}
