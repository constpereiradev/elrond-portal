<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {

            if (!$this->authService->authenticate($credentials)) {
                return $this->error([], 'The provided credentials do not match our records.');
            }

            $token = $this->authService->generateAuthToken();

            //TODO: Validar se o reino está ativo, se não tiver retorna erro.

            return $this->success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'type' => Auth::user()->type(),
            ]);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());;
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            try {

                if ($this->authService->logout($request->user())) {
                    return $this->success([]);
                }

                return $this->error([]);
            } catch (\Exception $e) {
                return $this->error([], $e->getMessage());;
            }
        }

        return $this->error([], 'User not found');;
    }
}
