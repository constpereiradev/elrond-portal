<?php

namespace App\Http\Controllers;

use App\Exceptions\CouncilException;
use App\Http\Requests\StoreCouncilRequest;
use App\Models\Council;
use App\Services\CouncilService;
use App\Services\LogService;
use Illuminate\Http\Request;

class CouncilController extends Controller
{
    public function __construct(
        private readonly CouncilService $councilService,
        private readonly LogService $logService
    ) {}

    public function index()
    {
        $councils = Council::all();
        return $this->success(['councils' => $councils]);
    }

    public function store(StoreCouncilRequest $request)
    {
        $this->councilService->validatePermission('store', $request->user(), Council::class);
        $request = $request->validated();

        try {
            $council = $this->councilService->store($request);

            return $this->success(['council' => $council]);
        } catch (\Exception $e) {
            $this->logService->logError('Failed to create council', ['error' => $e->getMessage()]);
            throw CouncilException::registerFailed();
        }
    }
}
