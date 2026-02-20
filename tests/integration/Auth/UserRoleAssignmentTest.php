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
final class UserRoleAssignmentTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testAdminRoleAssignmentReplacesPreviousRole(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        $employee = $this->findUserByEmail('employee@local.test');

        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->post(
            '/admin/users/' . $employee->id . '/role',
            $this->csrfPayload(['role' => 'it_staff'])
        );

        $response->assertRedirectTo('/admin/users');

        $updated = model(UserModel::class)->withGroups()->findById((int) $employee->id);

        $this->assertInstanceOf(User::class, $updated);
        $this->assertTrue($updated->inGroup('it_staff'));
        $this->assertFalse($updated->inGroup('employee'));
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new \RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }

    /**
     * @param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function csrfPayload(array $data): array
    {
        $hash = csrf_hash();
        $name = csrf_token();

        return $data + [$name => $hash];
    }
}
