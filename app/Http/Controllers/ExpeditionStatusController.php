<?php

namespace App\Http\Controllers;

use App\Models\ExpeditionStatus;
use Illuminate\Http\Request;

class ExpeditionStatusController extends Controller
{
    public function index()
    {
        return ExpeditionStatus::all();
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

            return $this->success(['status' => $status]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
