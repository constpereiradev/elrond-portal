<?php

namespace App\Http\Controllers;

use App\Exceptions\ExpeditionStatusException;
use App\Http\Requests\StoreExpeditionStatusRequest;
use App\Models\ExpeditionStatus;
use App\Services\ExpeditionStatusService;
use App\Services\LogService;
use Illuminate\Http\Request;

class ExpeditionStatusController extends Controller
{
    public function __construct(
        private readonly LogService $logService,
        private readonly ExpeditionStatusService $expeditionStatusService
    ) {}


    public function index()
    {
        $expeditionStatus = ExpeditionStatus::all();
        return $this->success(['expedition_statuses' => $expeditionStatus]);
    }

    public function store(StoreExpeditionStatusRequest $request)
    {
        $request = $request->validated();

        try {
            $status = $this->expeditionStatusService->register($request);

            return $this->success(['expedition_status' => $status]);
        } catch (\Exception $e) {
            dd($e);
            $this->logService->logError('Failed to create expedition status', ['error' => $e->getMessage()]);
            throw ExpeditionStatusException::registerFailed();
        }
    }
}
