<?php

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Services\Admin\UserManagementService;
use App\Services\Auth\AuthenticationService;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class UserManagementServiceTest extends CIUnitTestCase
{
    public function testCreateUserAssignsSelectedRoleAndSyncsPermissions(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth = $this->createMock(AuthenticationService::class);

        $auth->expects($this->once())
            ->method('register')
            ->with([
                'username' => 'tester',
                'email' => 'tester@local.test',
                'password' => 'Password@123',
            ])
            ->willReturn(42);

        $users->expects($this->once())
            ->method('assignGroup')
            ->with(42, 'employee');

        $users->expects($this->once())
            ->method('syncPermissions')
            ->with(
                42,
                ['reports.view', 'audit.view'],
                $this->callback(static function (array $allKnownPermissions): bool {
                    return in_array('reports.view', $allKnownPermissions, true)
                        && in_array('audit.view', $allKnownPermissions, true)
                        && in_array('procurement.pr.create', $allKnownPermissions, true);
                }),
            );

        $service = new UserManagementService($users, $auth);

        $userId = $service->createUser(
            [
                'username' => 'tester',
                'email' => 'tester@local.test',
                'password' => 'Password@123',
            ],
            'employee',
            ['reports.view', 'audit.view'],
        );

        $this->assertSame(42, $userId);
    }

    public function testDeleteUserRejectsDeletingOwnAccount(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth = $this->createMock(AuthenticationService::class);
        $user = new User([
            'id' => 7,
            'username' => 'tester',
            'email' => 'tester@local.test',
        ]);

        $users->expects($this->once())
            ->method('findById')
            ->with(7)
            ->willReturn($user);

        $users->expects($this->never())
            ->method('delete');

        $service = new UserManagementService($users, $auth);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('You cannot delete your own account.');

        $service->deleteUser(7, 7);
    }

    public function testUpdateModulePermissionGrantUsesConfiguredModulePermissions(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth = $this->createMock(AuthenticationService::class);
        $user = new User([
            'id' => 9,
            'username' => 'tester',
            'email' => 'tester@local.test',
        ]);

        $users->expects($this->once())
            ->method('findById')
            ->with(9)
            ->willReturn($user);

        $users->expects($this->once())
            ->method('grantPermissions')
            ->with(9, ['receiving.convert', 'receiving.view']);

        $service = new UserManagementService($users, $auth);
        $service->updateModulePermission(9, 'receiving', 'grant');
    }
}
