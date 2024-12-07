<?php

namespace App\Services\Auth;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function resendVerificationEmail(Request $request, $entityClass)
    {
        // Validate the request to ensure email is provided
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find the entity by email
        $entity = $entityClass::where('email', $request->email)->first();
        if (! $entity) {
            return response()->json(['message' => 'This email address is not registrated with us.'], 403);
        }

        // check if the entity is already verified
        if ($entity->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        // resend the verification email
        $entity->notify(new VerifyEmailNotification($entity));

        return response()->json(['message' => 'Verification email link sent successfully. It might take few minutes so please be patient.']);
    }

    public function verify(Request $request, $entityClass, $id, $hash)
    {
        $entity = $entityClass::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($entity->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        // if entity is already verified
        if ($entity->hasVerifiedEmail()) {
            return response()->json(['message' => 'Record already verified']);
        }

        // Mark the entity as verified
        if ($entity->markEmailAsVerified()) {
            event(new Verified($entity));
        }

        return response()->json(['message' => 'Record successfully verified. Please use Login endpoint to login.']);
    }

    public function login(Request $request, $entityClass)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $entity = $entityClass::where('email', $request->email)->first();
        if (! $entity || ! Hash::check($credentials['password'], $entity->password)) {
            return response()->json(['message' => __('Invalid login credentials')], 401);
        }

        // Check if the email is verified
        if (! $entity->hasVerifiedEmail()) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        // Delete any existing tokens for this entity
        $entity->tokens()->delete();

        // Generate a new Sanctum token
        $expiresAt = now()->addHours(48);
        $accessToken = $entity->createToken(config('app.name'), ['*'], $expiresAt)->plainTextToken;

        $refreshToken = Str::random(255);
        $refreshTokenExipresAt = now()->addDays(30);

        $entity->tokens()
            ->where('name', config('app.name'))
            ->where('expires_at', $expiresAt)
            ->update([
                'refresh_token' => $refreshToken,
                'refresh_token_expires_at' => $refreshTokenExipresAt,
            ]);

        return response()->json([
            'message' => 'Login successful',
            'token' => $accessToken,
            'token_expires_at' => $expiresAt,
            'refresh_token' => $refreshToken,
            'refresh_token_expires_at' => $refreshTokenExipresAt,
        ]);
    }

    public function logout(Request $request)
    {
        $entity = $request->user();

        $entity->tokens()->delete();

        $entity->currentAccessToken()->delete();

        return response()->json(['message' => 'You are logged out successfully.']);
    }

    public function refreshToken(PersonalAccessToken $token)
    {
        $expiresAt = now()->addHours(48);
        $newAccessToken = $token->tokenable->createToken(config('app.name'), ['*'], $expiresAt);

        return response()->json([
            'token' => $newAccessToken->plainTextToken,
            'token_expires_at' => $expiresAt,
        ]);
    }
}
