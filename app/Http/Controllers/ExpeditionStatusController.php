<?php

namespace App\Http\Controllers;

use App\Exceptions\ExpeditionStatusException;
use App\Models\ExpeditionStatus;
use App\Services\LogService;
use Illuminate\Http\Request;

class ExpeditionStatusController extends Controller
{
    public function __construct(private readonly LogService $logService) {}

    public function index()
    {
        $expeditionStatus = ExpeditionStatus::all();
        return $this->success(['expedition_statuses' => $expeditionStatus]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'description' => ['string']
        ]);

        try {
            $status = ExpeditionStatus::create([
                'status' => $request->status,
                'slug' => strtoupper($request->slug),
                'description' => $request->description
            ]);

            return $this->success(['expedition_status' => $status]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create expedition status', ['error' => $e->getMessage()]);
            throw ExpeditionStatusException::registerFailed();
        }
    }
}
