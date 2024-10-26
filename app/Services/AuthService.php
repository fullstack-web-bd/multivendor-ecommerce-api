<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Hash;
use Response;

class AuthService
{
    public function __construct(
        private readonly AuthRepository $authRepository
    ) {
    }

    public function getAuthData($user, $tokenInstance)
    {
        return [
            'user' => $user,
            'token' => $tokenInstance->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenInstance->token->expires_at),
            'permissions' => [], // TODO: Implement permissions.
        ];
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->authRepository->register($data);

        $tokenInstance = $user->createToken('authToken');

        return $this->getAuthData($user, $tokenInstance);
    }

    /**
     * @throws \Exception
     */
    public function login(array $credentials): array
    {
        $user = $this->authRepository->getUserByEmail($credentials['email']);

        if (!$user) {
            throw new \Exception("No user account found with this email address.", 404);
        }

        // Email is found. now check the password.
        if (!$this->isValidPassword($credentials['password'], $user->password)) {
            throw new \Exception("Email and password do not match, Please try again.", 401);
        }

        $tokenInstance = $user->createToken('authToken');
        return $this->getAuthData($user, $tokenInstance);
    }

    public function isValidPassword($givenPassword, $hashedPassword): bool
    {
        return Hash::check($givenPassword, $hashedPassword);
    }
}