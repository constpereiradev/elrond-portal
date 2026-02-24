<?php

namespace App\Http\Controllers;

use App\Exceptions\KingdomException;
use App\Http\Requests\StoreKingdomRequest;
use App\Models\Kingdom;
use App\Services\KingdomService;
use App\Services\LogService;

class KingdomController extends Controller
{
    public function __construct(
        private readonly KingdomService $kingdomService,
        private readonly LogService $logService
    ) {}

    public function index()
    {
        $kingdoms = Kingdom::all();
        return $this->success(['kingdoms' => $kingdoms]);
    }

    public function store(StoreKingdomRequest $request)
    {
        $this->kingdomService->validatePermission('store', $request->user(), Kingdom::class);
        $request = $request->validated();

        try {
            $kingdom = $this->kingdomService->store($request);

            return $this->success(['Kingdom' => $kingdom]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create kingdom', ['error' => $e->getMessage()]);

            throw KingdomException::registerFailed();
        }
    }
}
