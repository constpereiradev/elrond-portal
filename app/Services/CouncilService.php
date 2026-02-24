<?php

namespace App\Services;

use App\Models\Council;
use App\Services\Abstract\BaseService;

class CouncilService extends BaseService
{
    public function store(array $request): Council
    {
        return Council::create([
            'name' => $request['name'],
            'description' => $request['description'] ?? null
        ]);
    }
}
