<?php

namespace App\Repositories\Contracts\Auth;

use CodeIgniter\Shield\Entities\User;

interface UserRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?object;

    /**
     * @param array{username: string, email: string, password: string} $data
     */
    public function createUser(array $data): int;

    /**
     * @return list<User>
     */
    public function findAllWithGroups(): array;

    public function findById(int $userId): ?User;

    public function assignGroup(int $userId, string $group): void;

    public function save(User $user): void;

    public function delete(int $userId): void;

    /**
     * @param string[] $grantedPermissions
     * @param string[] $allKnownPermissions
     */
    public function syncPermissions(int $userId, array $grantedPermissions, array $allKnownPermissions): void;

    /**
     * @param string[] $permissions
     */
    public function grantPermissions(int $userId, array $permissions): void;

    /**
     * @param string[] $permissions
     */
    public function revokePermissions(int $userId, array $permissions): void;

    public function userInGroup(int $userId, string $group): bool;
}
