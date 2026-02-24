<?php

namespace App\Services;

use App\Http\Requests\StoreExpeditionRequest;
use App\Models\ExpeditionStatus;
use App\Services\Abstract\BaseService;

class ExpeditionStatusService extends BaseService
{

    public function register(array $request): ExpeditionStatus
    {
        return ExpeditionStatus::create([
            'status' => $request['status'],
            'slug' => strtoupper($request['slug']),
            'description' => $request['description'] ?? null,
        ]);
    }
}
