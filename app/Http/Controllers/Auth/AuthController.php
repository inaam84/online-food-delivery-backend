<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

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
}
