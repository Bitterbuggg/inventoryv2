<?php

namespace App\Repositories\EloquentLike\Auth;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use RuntimeException;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly UserModel $userModel = new UserModel())
    {
    }

    public function findByIdentifier(string $identifier): ?object
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->userModel->findByCredentials(['email' => strtolower($identifier)]);
        }

        return $this->userModel->findByCredentials(['username' => $identifier]);
    }

    public function createUser(array $data): int
    {
        $user = $this->userModel->createNewUser([
            'username' => $data['username'],
            'email'    => strtolower($data['email']),
            'password' => $data['password'],
            'active'   => 1,
        ]);

        $this->userModel->save($user);

        if ($user->id === null) {
            throw new RuntimeException('Failed to persist user record.');
        }

        return (int) $user->id;
    }

    public function assignGroup(int $userId, string $group): void
    {
        $user = $this->getUserOrFail($userId);

        if (! $user->inGroup($group)) {
            $user->addGroup($group);
        }
    }

    public function userInGroup(int $userId, string $group): bool
    {
        $user = $this->userModel->withGroups()->findById($userId);

        if (! $user instanceof User) {
            return false;
        }

        return $user->inGroup($group);
    }

    private function getUserOrFail(int $userId): User
    {
        $user = $this->userModel->withGroups()->findById($userId);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$userId} not found.");
        }

        return $user;
    }
}
