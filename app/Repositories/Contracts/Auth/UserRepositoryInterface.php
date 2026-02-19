<?php

namespace App\Repositories\Contracts\Auth;

interface UserRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?object;

    /**
     * @param array{username: string, email: string, password: string} $data
     */
    public function createUser(array $data): int;

    public function assignGroup(int $userId, string $group): void;

    public function userInGroup(int $userId, string $group): bool;
}

