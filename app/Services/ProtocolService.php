<?php

namespace App\Services;

use App\Models\Expedition;
use App\Models\ExpeditionProtocol;

class ProtocolService
{
    public function generateProtocol(Expedition $expedition): ExpeditionProtocol
    {
        return ExpeditionProtocol::create([
            'expedition_id' => $expedition->id,
        ]);
    }

    public function getExpedition(ExpeditionProtocol $expeditionProtocol): ?Expedition
    {
        return Expedition::find($expeditionProtocol->expedition_id);
    }
}
