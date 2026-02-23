<?php

namespace App\Services;

use App\Http\Requests\StoreCouncilRequest;
use App\Models\Council;
use App\Services\Abstract\BaseService;

class CouncilService extends BaseService
{
    public function store(StoreCouncilRequest $request): Council
    {
        return Council::create([
            'name' => $request->name,
            'description' => $request->description ?? null
        ]);
    }
}
