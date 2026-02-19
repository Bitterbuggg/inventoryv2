<?php

use App\Database\Seeds\AuthRbacSeeder;
use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class LoginFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testLoginPageLoads(): void
    {
        $response = $this->get('/login');
        $response->assertOK();
    }

    public function testLoginSucceedsWithValidCredentials(): void
    {
        $payload = $this->csrfPayload([
            'identifier' => 'admin@local.test',
            'password'   => 'Admin@1234',
        ]);

        $response = $this->withSession(session()->get())->post('/login', $payload);
        $response->assertRedirectTo('/admin/dashboard');
    }

    public function testLoginFailsWithInvalidCredentials(): void
    {
        $payload = $this->csrfPayload([
            'identifier' => 'admin@local.test',
            'password'   => 'wrong-password',
        ]);

        $response = $this->withSession(session()->get())->post('/login', $payload);
        $response->assertRedirect();
    }

    public function testLoginWithoutCsrfTokenIsRejected(): void
    {
        $this->expectException(SecurityException::class);

        $this->post('/login', [
            'identifier' => 'admin@local.test',
            'password'   => 'Admin@1234',
        ]);
    }

    public function testPasswordIsStoredHashed(): void
    {
        $user = model(UserModel::class)->findByCredentials(['email' => 'admin@local.test']);

        $this->assertNotNull($user);
        $this->assertNotSame('Admin@1234', $user->password_hash);
        $this->assertTrue(password_verify('Admin@1234', $user->password_hash));
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
