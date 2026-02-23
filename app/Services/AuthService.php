<?php

namespace App\Services;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function AuthenticateAndGenerateToken(array $credentials): string
    {
        if (!$this->authenticate($credentials)) {
            throw AuthException::invalidCredentials();
        }

        return $this->generateAuthToken();
    }

    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function generateAuthToken(): string
    {
        $user = Auth::user();

        if (!$user) {
            throw AuthException::authFailed();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function logout($user): bool
    {
        $token = $user->currentAccessToken();

        if (!$token instanceof PersonalAccessToken) {
            throw AuthException::invalidToken();
        }

        $token->delete();
        return true;
    }
}
