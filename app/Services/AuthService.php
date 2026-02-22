<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function generateAuthToken(): string
    {
        $token = Auth::user()->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function logout($user): bool
    {
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
            return true;
        }

        return false;
    }
}
