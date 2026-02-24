<?php

namespace App\Services;

use App\Models\Kingdom;
use App\Services\Abstract\BaseService;

class KingdomService extends BaseService
{
    public function store(array $request): Kingdom
    {
        return Kingdom::create([
            'name' => $request['name'],
            'description' => $request['description'] ?? null
        ]);
    }
}
