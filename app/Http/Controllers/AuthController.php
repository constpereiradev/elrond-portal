<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * Docs: https://laravel.com/docs/12.x/sanctum
     */
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {

            if (!Auth::attempt($credentials)) {
                return $this->error([], 'The provided credentials do not match our records.');
            }

            $token = Auth::user()->createToken('auth_token')->plainTextToken;

            return $this->success([
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());;
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            try {
                $token = $request->user()->currentAccessToken();
                
                if ($token instanceof PersonalAccessToken) {
                    $token->delete();
                    return $this->success();
                }

                return $this->error([], 'Invalid token');
            } catch (\Exception $e) {
                return $this->error([], $e->getMessage());;
            }
        }

        return $this->error([], 'User not found');;
    }
}
