<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Exceptions\UserException;
use App\Http\Requests\AuthenticateRequest;
use App\Services\AuthService;
use App\Services\LogService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly UserService $userService,
        private readonly LogService $logService,
    ) {}

    #[OA\Post(
        path: '/api/v1/auth',
        summary: 'Autentica um usuário',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'email', type: 'string', example: 'admin@gmail.com'),
                        new OA\Property(property: 'password', type: 'string', example: '1234'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário autenticado com sucesso',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'user_token',
                                type: 'object',
                                example: [
                                    'access_token' => 'example_token',
                                    'token_type' => 'Bearer',
                                    'type' => 'user',
                                ]
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Não autenticado'
            )
        ]
    )]
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
        } catch (AuthException $e) {
            $this->logService->logError('Auth failed: ' . $e->getMessage(), ['exception' => $e]);

            throw $e;
        } catch (UserException $e) {
            $this->logService->logError('Auth failed: ' . $e->getMessage(), ['exception' => $e]);

            throw $e;
        } catch (\Exception $e) {
            $this->logService->logError('Auth failed: ' . $e->getMessage(), ['exception' => $e]);

            throw AuthException::authFailed();
        }
    }

    #[OA\Post(
        path: '/api/v1/logout',
        summary: 'Logout do usuário',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout realizado com sucesso',
            ),
            new OA\Response(
                response: 401,
                description: 'Não autenticado'
            )
        ]
    )]
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
        } catch (AuthException $e) {
            $this->logService->logError('Logout failed: ' . $e->getMessage(), ['exception' => $e]);

            throw $e;
        } catch (\Exception $e) {
            $this->logService->logError('Logout failed: ' . $e->getMessage(), ['exception' => $e]);
            throw AuthException::logoutFailed();
        }
    }
}
