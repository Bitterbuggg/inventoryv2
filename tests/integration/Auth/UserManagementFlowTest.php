<?php

use App\Database\Seeds\AuthRbacSeeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class UserManagementFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testAdminCanCreateEmployeeUserWithPermissionOverrides(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->post(
            '/admin/users',
            $this->csrfPayload([
                'username' => 'custom.staff',
                'email' => 'custom.staff@local.test',
                'password' => 'Password@123',
                'password_confirm' => 'Password@123',
                'role' => 'employee',
                'permissions' => ['reports.view', 'audit.view'],
            ]),
        );

        $response->assertRedirectTo('/admin/users');

        $created = model(UserModel::class)->findByCredentials(['email' => 'custom.staff@local.test']);

        $this->assertInstanceOf(User::class, $created);
        $reloaded = model(UserModel::class)->withGroups()->findById((int) $created->id);

        $this->assertInstanceOf(User::class, $reloaded);
        $this->assertTrue($reloaded->inGroup('employee'));
        $this->assertTrue($reloaded->hasPermission('reports.view'));
        $this->assertTrue($reloaded->hasPermission('audit.view'));
    }

    public function testAdminCanUpdateUserProfileAndPermissions(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->post(
            '/admin/users/' . $employee->id,
            $this->csrfPayload([
                'username' => 'employee.updated',
                'email' => 'employee.updated@local.test',
                'permissions' => ['reports.view', 'audit.view'],
            ]),
        );

        $response->assertRedirectTo('/admin/users');

        $updated = model(UserModel::class)->withGroups()->findById((int) $employee->id);

        $this->assertInstanceOf(User::class, $updated);
        $this->assertSame('employee.updated', (string) $updated->username);
        $this->assertSame('employee.updated@local.test', (string) $updated->email);
        $this->assertTrue($updated->hasPermission('reports.view'));
        $this->assertTrue($updated->hasPermission('audit.view'));
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function csrfPayload(array $data): array
    {
        $hash = csrf_hash();
        $name = csrf_token();

        return $data + [$name => $hash];
    }
}
