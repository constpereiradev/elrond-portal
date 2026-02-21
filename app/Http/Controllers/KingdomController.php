<?php

namespace App\Http\Controllers;

use App\Models\Kingdom;
use Illuminate\Http\Request;

class KingdomController extends Controller
{
    public function index()
    {
        return Kingdom::all();
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('store', Kingdom::class)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['string', 'max:10']
        ]);

        try {
            $kingdom = Kingdom::create([
                'name' => $request->name,
                'description' => $request->description ?? null
            ]);

            return $this->success(['Kingdom' => $kingdom]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function update(int $id, Request $request)
    {
        if ($request->user()->cannot('update', Kingdom::class)) {
            abort(403);
        }

        $kingdom = Kingdom::find($id);

        if (!$kingdom) {
            return $this->error([], 'Kingdom not found');
        }

        $request->validate([
            'name' => ['sometimes', 'string'],
            'description' =>  ['sometimes', 'string', 'max:120'],
            'active' => ['sometimes', 'string', 'in:a,i'],
        ]);

        try {

            if (!empty($request->name)) {
                $kingdom->name = $request->name;
            }

            if (!empty($request->description)) {
                $kingdom->description = $request->description;
            }

            if (!empty($request->active)) {
                $kingdom->active = $request->active;
            }

            $kingdom->save();

            return $this->success(['Kingdom' => $kingdom]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
