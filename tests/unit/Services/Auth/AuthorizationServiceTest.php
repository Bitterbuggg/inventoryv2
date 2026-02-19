<?php

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Services\Auth\AuthorizationService;
use CodeIgniter\Exceptions\PageForbiddenException;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AuthorizationServiceTest extends CIUnitTestCase
{
    public function testUserHasGroupReturnsRepositoryValue(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $users->expects($this->once())
            ->method('userInGroup')
            ->with(10, 'admin')
            ->willReturn(true);

        $service = new AuthorizationService($users);

        $this->assertTrue($service->userHasGroup(10, 'admin'));
    }

    public function testAssertGroupAccessPassesWhenAnyGroupMatches(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $users->method('userInGroup')->willReturnCallback(
            static fn (int $userId, string $group): bool => $userId === 5 && $group === 'admin',
        );

        $service = new AuthorizationService($users);
        $service->assertGroupAccess(5, ['employee', 'admin']);

        $this->assertTrue(true);
    }

    public function testAssertGroupAccessThrowsWhenNoGroupMatches(): void
    {
        $users = $this->createMock(UserRepositoryInterface::class);
        $users->method('userInGroup')->willReturn(false);

        $service = new AuthorizationService($users);

        $this->expectException(PageForbiddenException::class);
        $service->assertGroupAccess(7, ['admin']);
    }
}

