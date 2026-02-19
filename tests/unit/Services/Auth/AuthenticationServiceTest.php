<?php

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Services\Auth\AuthenticationService;
use CodeIgniter\Shield\Authentication\AuthenticatorInterface;
use CodeIgniter\Shield\Result;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AuthenticationServiceTest extends CIUnitTestCase
{
    public function testRegisterCreatesEmployeeUser(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth  = $this->createMock(AuthenticatorInterface::class);

        $users->method('findByIdentifier')->willReturn(null);
        $users->expects($this->once())
            ->method('createUser')
            ->willReturn(99);
        $users->expects($this->once())
            ->method('assignGroup')
            ->with(99, 'employee');

        $service = new AuthenticationService($users, $auth);
        $userId  = $service->register([
            'username' => 'tester',
            'email'    => 'tester@local.test',
            'password' => 'Password@123',
        ]);

        $this->assertSame(99, $userId);
    }

    public function testRegisterFailsWhenUsernameExists(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth  = $this->createMock(AuthenticatorInterface::class);

        $users->method('findByIdentifier')->willReturnCallback(
            static fn (string $identifier) => $identifier === 'tester' ? (object) ['id' => 1] : null,
        );

        $service = new AuthenticationService($users, $auth);

        $this->expectException(DomainException::class);
        $service->register([
            'username' => 'tester',
            'email'    => 'new@local.test',
            'password' => 'Password@123',
        ]);
    }

    public function testLoginSuccessReturnsTrue(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth  = $this->createMock(AuthenticatorInterface::class);

        $auth->expects($this->once())
            ->method('attempt')
            ->with(['email' => 'admin@local.test', 'password' => 'Admin@1234'])
            ->willReturn(new Result(['success' => true]));

        $service = new AuthenticationService($users, $auth);

        $this->assertTrue($service->login('admin@local.test', 'Admin@1234'));
        $this->assertNull($service->getLastError());
    }

    public function testLoginFailureReturnsFalseAndStoresError(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth  = $this->createMock(AuthenticatorInterface::class);

        $auth->method('attempt')->willReturn(new Result([
            'success' => false,
            'reason'  => 'Invalid password',
        ]));

        $service = new AuthenticationService($users, $auth);

        $this->assertFalse($service->login('admin@local.test', 'bad-password'));
        $this->assertSame('Invalid password', $service->getLastError());
    }

    public function testLogoutCallsAuthenticatorLogout(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $auth  = $this->createMock(AuthenticatorInterface::class);

        $auth->expects($this->once())->method('logout');

        $service = new AuthenticationService($users, $auth);
        $service->logout();
    }
}

