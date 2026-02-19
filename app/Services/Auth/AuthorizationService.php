<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use CodeIgniter\Exceptions\PageForbiddenException;

class AuthorizationService
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function userHasGroup(int $userId, string $group): bool
    {
        return $this->users->userInGroup($userId, $group);
    }

    /**
     * @param string[] $allowedGroups
     */
    public function assertGroupAccess(int $userId, array $allowedGroups): void
    {
        foreach ($allowedGroups as $group) {
            if ($this->userHasGroup($userId, $group)) {
                return;
            }
        }

        throw PageForbiddenException::forPageForbidden('Insufficient role privileges.');
    }
}

