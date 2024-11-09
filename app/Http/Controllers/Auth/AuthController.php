<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        return $this->authService->login($request, User::class);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    public function resendVerificationEmail(Request $request)
    {
        return $this->authService->resendVerificationEmail($request, User::class);
    }

    public function verify(Request $request, $id, $hash)
    {
        return $this->authService->verify($request, User::class, $id, $hash);
    }

    public function refreshToken(Request $request)
    {
        $request->validate(['refresh_token' => 'required|string|min:255|max:255']);

        $token = PersonalAccessToken::where('refresh_token', $request->refresh_token)->first();

        if (! $token || $token->refresh_token_expires_at < now()) {
            return jsonResponse([
                'message' => 'Invalid or expired refresh token. Please login again',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->authService->refreshToken($token);
    }
}
