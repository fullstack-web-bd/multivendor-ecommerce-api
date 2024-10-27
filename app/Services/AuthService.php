<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Hash;
use Mail;

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

    public function sendResetCode($email): bool
    {
        $user = $this->authRepository->getUserByEmail($email);

        if (!$user) {
            throw new \Exception("No user account found with this email address. Please try with a valid email address.", 404);
        }

        // Send reset code to user's email.
        $code = mt_rand(100000, 999999);
        $user->reset_code = $code;
        $user->reset_code_expires_at = Carbon::now()->addMinutes(10); // 10 minutes.
        $user->save();

        // Send email code to user's email.
        Mail::send('emails.reset_code', ['code' => $code], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Reset Code');
        });

        return true;
    }

    private function verifyResetCode(User $user, $code): bool
    {
        if ($user->reset_code !== $code) {
            throw new \Exception("Invalid reset code. Please try again with a valid reset code.", 401);
        }

        if ($user->reset_code_expires_at < Carbon::now()) {
            throw new \Exception("Reset code has been expired. Please try again with a new reset code.", 401);
        }

        return true;
    }

    public function resetPassword(array $data): array
    {
        $user = $this->authRepository->getUserByEmail($data['email']);

        if (!$user) {
            throw new \Exception("No user account found with this email address. Please try with a valid email address.", 404);
        }

        $this->verifyResetCode($user, $data['reset_code']);

        $user->password = Hash::make($data['password']);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->save();

        $tokenInstance = $user->createToken('authToken');

        return $this->getAuthData($user, $tokenInstance);
    }

    public function logout( $email ): bool
    {
        $user = $this->authRepository->getUserByEmail($email);

        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return true;
    }
}