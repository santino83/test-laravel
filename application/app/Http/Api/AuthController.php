<?php

namespace App\Http\Api;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController
{

    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Login entrypoint
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->getCredentials();

        $success = $this->authService->login($credentials, true);
        if ($success) return response()->json(['token' => $success, 'success' => true]);

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    /**
     * Logout entrypoint
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['success' => true]);
    }

    public function user(): mixed
    {
        return Auth::user();
    }

}
