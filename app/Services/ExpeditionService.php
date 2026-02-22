<?php

namespace App\Services;

use App\Models\Expedition;
use App\Models\ExpeditionProtocol;

class ExpeditionService
{
    public function getByProtocol(string $protocolId): mixed
    {
        $protocol = ExpeditionProtocol::where('uuid', $protocolId)->first();

        if(!$protocol){
            return [];
            //TODO: Adicionar exceptions
        }

        return Expedition::find($protocol->expedition_id);
    }
}