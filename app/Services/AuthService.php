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
        if (!$user) {
            throw AuthException::invalidToken();
        }

        $token = $user->currentAccessToken();

        if ($token && method_exists($token, 'delete')) {
            return $token->delete();
        }

        // Se não houver token mas o usuário possui sessão (Caso de Web)
        // deleta todos os tokens dele
        return $user->tokens()->delete();
    }
}
