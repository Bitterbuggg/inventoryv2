<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Services\Auth\AuthenticationService;
use CodeIgniter\Shield\Entities\User;
use DomainException;

class UserManagementService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly AuthenticationService $authentication,
    )
    {
    }

    /**
     * @return list<User>
     */
    public function listUsers(): array
    {
        return $this->users->findAllWithGroups();
    }

    public function findUser(int $userId): ?User
    {
        return $this->users->findById($userId);
    }

    /**
     * @param array{username: string, email: string, password: string} $data
     * @param string[] $permissions
     */
    public function createUser(array $data, string $roleSelection, array $permissions): int
    {
        $roleSelection = trim($roleSelection);

        if (! in_array($roleSelection, $this->assignableRoles(), true)) {
            throw new DomainException('Invalid role selected.');
        }

        $userId = $this->authentication->register($data);

        $this->users->assignGroup($userId, $roleSelection);
        $this->users->syncPermissions($userId, $permissions, $this->allKnownPermissions());

        return $userId;
    }

    /**
     * @param string[] $permissions
     */
    public function updateUser(int $userId, string $username, string $email, array $permissions): void
    {
        $user = $this->requireUser($userId);
        $email = strtolower(trim($email));
        $username = trim($username);

        $this->assertUniqueIdentifier($userId, $email, 'Email already exists.');
        $this->assertUniqueIdentifier($userId, $username, 'Username already exists.');

        $user->email = $email;
        $user->username = $username;

        $this->users->save($user);
        $this->users->syncPermissions($userId, $permissions, $this->allKnownPermissions());
    }

    public function deleteUser(int $userId, ?int $currentUserId = null): void
    {
        $user = $this->requireUser($userId);

        if ($currentUserId !== null && $currentUserId === $userId) {
            throw new DomainException('You cannot delete your own account.');
        }

        if ($user->inGroup('admin')) {
            throw new DomainException('Cannot delete admin users.');
        }

        $this->users->delete($userId);
    }

    public function assignRole(int $userId, string $newRole): void
    {
        $newRole = trim($newRole);

        if (! in_array($newRole, $this->assignableRoles(), true)) {
            throw new DomainException('Invalid role selected.');
        }

        $this->requireUser($userId);
        $this->users->assignGroup($userId, $newRole);
    }

    public function updateModulePermission(int $userId, string $module, string $action): void
    {
        $module = trim($module);
        $action = trim($action);
        $modulePermissions = $this->modulePermissions();

        if (! array_key_exists($module, $modulePermissions)) {
            throw new DomainException('Invalid module selected.');
        }

        if (! in_array($action, ['grant', 'revoke'], true)) {
            throw new DomainException('Invalid action selected.');
        }

        $this->requireUser($userId);
        $permissions = $modulePermissions[$module];

        if ($action === 'grant') {
            $this->users->grantPermissions($userId, $permissions);

            return;
        }

        $this->users->revokePermissions($userId, $permissions);
    }

    /**
     * @return array<string, array<string, array{label: string, desc: string}>>
     */
    public function permissionStructure(): array
    {
        return [
            'Procurement' => [
                'procurement.pr.create' => ['label' => 'Create PRs', 'desc' => 'Draft and submit requests.'],
                'procurement.pr.approve' => ['label' => 'Approve PRs', 'desc' => 'Approval authority.'],
                'procurement.po.create' => ['label' => 'Manage POs', 'desc' => 'Generate vendor orders.'],
                'procurement.por.manage' => ['label' => 'Manage PO Requests', 'desc' => 'Approve receiving handoff records.'],
                'procurement.view' => ['label' => 'View Data', 'desc' => 'Read-only procurement access.'],
            ],
            'Inventory & Issuance' => [
                'inventory.view' => ['label' => 'View Inventory', 'desc' => 'See quantities and issuance records.'],
                'inventory.issuance.create' => ['label' => 'Request Issuance', 'desc' => 'Request stock pulls.'],
                'inventory.issuance.approve' => ['label' => 'Approve Release', 'desc' => 'Approve and release stock.'],
                'inventory.quantity.update' => ['label' => 'Stock Adjustments', 'desc' => 'Manual stock corrections.'],
            ],
            'Receiving & Operations' => [
                'receiving.view' => ['label' => 'View Receiving', 'desc' => 'Inspect receiving records.'],
                'receiving.convert' => ['label' => 'Log Receiving', 'desc' => 'Verify vendor deliveries.'],
                'reports.view' => ['label' => 'System Reports', 'desc' => 'View analytics and reports.'],
                'audit.view' => ['label' => 'Audit Logs', 'desc' => 'View system history.'],
            ],
        ];
    }

    /**
     * @return array<string, string[]>
     */
    public function rolePresets(): array
    {
        return [
            'admin' => [
                'procurement.pr.create',
                'procurement.pr.approve',
                'procurement.po.create',
                'procurement.por.manage',
                'procurement.view',
                'inventory.view',
                'inventory.issuance.create',
                'inventory.issuance.approve',
                'inventory.quantity.update',
                'receiving.view',
                'receiving.convert',
                'reports.view',
                'audit.view',
            ],
            'it_staff' => [
                'procurement.view',
                'procurement.pr.approve',
                'inventory.view',
                'inventory.quantity.update',
                'receiving.view',
                'receiving.convert',
                'reports.view',
                'audit.view',
            ],
            'employee' => [
                'procurement.pr.create',
                'procurement.view',
                'inventory.view',
                'inventory.issuance.create',
            ],
        ];
    }

    /**
     * @return array<string, string[]>
     */
    public function modulePermissions(): array
    {
        return [
            'procurement' => [
                'procurement.pr.create',
                'procurement.pr.approve',
                'procurement.po.create',
                'procurement.por.manage',
                'procurement.view',
            ],
            'receiving' => [
                'receiving.convert',
                'receiving.view',
            ],
            'inventory' => [
                'inventory.view',
                'inventory.quantity.update',
                'inventory.issuance.create',
                'inventory.issuance.approve',
            ],
            'reports' => [
                'reports.view',
            ],
        ];
    }

    /**
     * @return array<string, string[]>
     */
    public function moduleBadgePermissions(): array
    {
        return [
            'Procurement' => $this->modulePermissions()['procurement'],
            'Receiving' => $this->modulePermissions()['receiving'],
            'Inventory' => $this->modulePermissions()['inventory'],
            'Reports' => $this->modulePermissions()['reports'],
        ];
    }

    /**
     * @return string[]
     */
    public function roleSelections(): array
    {
        return $this->assignableRoles();
    }

    /**
     * @return string[]
     */
    public function assignableRoles(): array
    {
        return ['admin', 'it_staff', 'employee'];
    }

    /**
     * @return string[]
     */
    public function allKnownPermissions(): array
    {
        $allPermissions = [];

        foreach ($this->permissionStructure() as $groupPermissions) {
            foreach (array_keys($groupPermissions) as $permission) {
                $allPermissions[$permission] = $permission;
            }
        }

        return array_values($allPermissions);
    }

    private function requireUser(int $userId): User
    {
        $user = $this->findUser($userId);

        if (! $user instanceof User) {
            throw new DomainException('User not found.');
        }

        return $user;
    }

    private function assertUniqueIdentifier(int $currentUserId, string $identifier, string $message): void
    {
        $existing = $this->users->findByIdentifier($identifier);

        if ($existing === null) {
            return;
        }

        if ((int) ($existing->id ?? 0) === $currentUserId) {
            return;
        }

        throw new DomainException($message);
    }
}
