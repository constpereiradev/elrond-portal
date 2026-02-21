<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error([], 'User not found');
        }

        return $this->success(['user' => $user]);
    }


    public function getLogged(Request $request)
    {
        return $this->success(['user' => $request->user()]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' =>  ['required', 'string'],
            "email" =>  ['required', 'email'],
            "password" =>  ['required', 'string'],
            'role_id' => ['required', 'integer', 'exists:roles,id,status,a'],
            'kingdom_id' => ['integer', 'exists:kingdoms,id,status,a'],
            'council_id' => ['integer', 'exists:council,id,status,a'],
        ]);

        try {

            $role = Role::find($request->role_id);
            if ($role->slug == RoleEnum::admin) {

                if ($request->user()->cannot('storeAdmin', User::class)) {
                    abort(403);
                }
            }

            $user = User::create([

                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                'role_id' => $request->role_id,
                'kindom_id' => $request->kindom_id ?? null, //TODO: Adicionar validação para enviar somente 1.
                'council_id' => $request->council_id ?? null,
            ]);

            if ($user) {
                return $this->success(['id' => $user->id]);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function updateLogged(Request $request)
    {
        try {
            $user = $request->user();

            if (!empty($request->name)) {
                $user->name = $request->name;
            }

            if (!empty($request->email)) {
                $user->email = $request->email;
            }

            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            return $this->success(['user' => $user]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function update(int $id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error([], 'User not found');
        }

        try {

            if (!empty($request->name)) {
                $user->name = $request->name;
            }

            if (!empty($request->email)) {
                $user->email = $request->email;
            }

            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            return $this->success(['user' => $user]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }


    public function destroy(int $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->error([], 'User not found');
            }

            $user->delete();

            return $this->success();
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function destroyLogged(Request $request)
    {
        try {
            $user = $request->user();
            $user->delete();

            return $this->success();
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
