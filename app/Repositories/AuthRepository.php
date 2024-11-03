<?php

declare(strict_types=1);

namespace App\Repositories;
use App\Models\User;

class AuthRepository
{
    public function register($data): User
    {
        return User::create($data);
    }

    public function getUserByEmail($email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUserByRole($role, $email): ?User
    {
        return User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })
            ->where('email', $email)
            ->first();
    }
}