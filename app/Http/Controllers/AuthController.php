<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\AuthenticateRequest;
use App\Services\AuthService;
use App\Services\LogService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly UserService $userService,
        private readonly LogService $logService,
    ) {}

    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        try {
            $token = $this->authService->AuthenticateAndGenerateToken($credentials);
            $user = Auth::user();

            $this->userService->validateUserStatus($user);

            return $this->success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'type' => $user->type(),
            ]);
        } catch (\Exception $e) {
            $this->logService->logError('Auth failed: ' . $e->getMessage(), ['exception' => $e]);

            throw AuthException::authFailed();
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if (!$request->user()) {
            throw AuthException::userNotFound();
        }

        try {
            if ($this->authService->logout($request->user())) {
                return $this->success([]);
            }
            
            throw AuthException::logoutFailed();
        } catch (\Exception $e) {
            $this->logService->logError('Logout failed: ' . $e->getMessage(), ['exception' => $e]);
            throw AuthException::logoutFailed();
        }
    }
}
