<?php

namespace App\Http\Controllers;

use App\Models\Council;
use Illuminate\Http\Request;

class CouncilController extends Controller
{
    public function index()
    {
        return Council::all();
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('store', Council::class)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['string', 'max:10']
        ]);

        try {
            $council = Council::create([
                'name' => $request->name,
                'description' => $request->description ?? null
            ]);

            return $this->success(['council' => $council]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function update(int $id, Request $request)
    {
        if ($request->user()->cannot('update', Council::class)) {
            abort(403);
        }

        $council = Council::find($id);

        if (!$council) {
            return $this->error([], 'Council not found');
        }

        $request->validate([
            'name' => ['sometimes', 'string'],
            'description' =>  ['sometimes', 'string', 'max:120'],
            'active' => ['sometimes', 'string', 'in:a,i'],
        ]);

        try {

            if (!empty($request->name)) {
                $council->name = $request->name;
            }

            if (!empty($request->description)) {
                $council->description = $request->description;
            }

            if (!empty($request->active)) {
                $council->active = $request->active;
            }

            $council->save();

            return $this->success(['council' => $council]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
