<?php

namespace App\Http\Controllers\User;

use App\Events\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;
use App\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->validated());

        event(new RegisteredEvent($user));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }
}
