<?php

namespace App\Repositories\EloquentLike\Auth;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use RuntimeException;

class UserRepository implements UserRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?object
    {
        $userModel  = $this->newUserModel();
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $userModel->findByCredentials(['email' => strtolower($identifier)]);
        }

        return $userModel->findByCredentials(['username' => $identifier]);
    }

    public function createUser(array $data): int
    {
        $userModel = $this->newUserModel();
        $user      = $userModel->createNewUser([
            'username' => $data['username'],
            'email'    => strtolower($data['email']),
            'password' => $data['password'],
            'active'   => 1,
        ]);

        $userId = $userModel->insert($user, true);

        if (! is_int($userId) && ! ctype_digit((string) $userId)) {
            throw new RuntimeException('Failed to persist user record.');
        }

        return (int) $userId;
    }

    public function findAllWithGroups(): array
    {
        $users = $this->newUserModel()->withGroups()->findAll();

        return array_values(array_filter($users, static fn ($user): bool => $user instanceof User));
    }

    public function findById(int $userId): ?User
    {
        $user = $this->newUserModel()->withGroups()->findById($userId);

        return $user instanceof User ? $user : null;
    }

    public function assignGroup(int $userId, string $group): void
    {
        $user = $this->getUserOrFail($userId);
        $group = strtolower(trim($group));

        if ($group === '') {
            throw new RuntimeException('Group cannot be empty.');
        }

        // Roles are single-assignment in this project, so replace existing groups.
        $user->syncGroups($group);
    }

    public function save(User $user): void
    {
        if ($this->newUserModel()->save($user) === false) {
            throw new RuntimeException('Failed to save user record.');
        }
    }

    public function delete(int $userId): void
    {
        if ($this->newUserModel()->delete($userId) === false) {
            throw new RuntimeException("Failed to delete user {$userId}.");
        }
    }

    public function syncPermissions(int $userId, array $grantedPermissions, array $allKnownPermissions): void
    {
        $user = $this->getUserOrFail($userId);
        $grantedPermissions = $this->normalizePermissions($grantedPermissions);

        foreach ($this->normalizePermissions($allKnownPermissions) as $permission) {
            if (in_array($permission, $grantedPermissions, true)) {
                if (! $user->hasPermission($permission)) {
                    $user->addPermission($permission);
                }

                continue;
            }

            if ($user->hasPermission($permission)) {
                $user->removePermission($permission);
            }
        }
    }

    public function grantPermissions(int $userId, array $permissions): void
    {
        $user = $this->getUserOrFail($userId);

        foreach ($this->normalizePermissions($permissions) as $permission) {
            if (! $user->hasPermission($permission)) {
                $user->addPermission($permission);
            }
        }
    }

    public function revokePermissions(int $userId, array $permissions): void
    {
        $user = $this->getUserOrFail($userId);

        foreach ($this->normalizePermissions($permissions) as $permission) {
            if ($user->hasPermission($permission)) {
                $user->removePermission($permission);
            }
        }
    }

    public function userInGroup(int $userId, string $group): bool
    {
        $user = $this->newUserModel()->withGroups()->findById($userId);

        if (! $user instanceof User) {
            return false;
        }

        return $user->inGroup($group);
    }

    private function getUserOrFail(int $userId): User
    {
        $user = $this->newUserModel()->withGroups()->findById($userId);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$userId} not found.");
        }

        return $user;
    }

    private function newUserModel(): UserModel
    {
        return new UserModel();
    }

    /**
     * @param string[] $permissions
     *
     * @return string[]
     */
    private function normalizePermissions(array $permissions): array
    {
        $normalized = [];

        foreach ($permissions as $permission) {
            $permission = trim((string) $permission);

            if ($permission === '') {
                continue;
            }

            $normalized[$permission] = $permission;
        }

        return array_values($normalized);
    }
}
