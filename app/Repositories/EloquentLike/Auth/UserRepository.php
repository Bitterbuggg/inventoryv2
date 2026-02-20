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
}
