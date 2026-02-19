<?php

use App\Database\Seeds\AuthRbacSeeder;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class SignupFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testSignupPageLoads(): void
    {
        $response = $this->get('/signup');
        $response->assertOK();
    }

    public function testSignupSucceedsWithValidPayload(): void
    {
        $payload = $this->csrfPayload([
            'username'         => 'newuser',
            'email'            => 'newuser@local.test',
            'password'         => 'Password@123',
            'password_confirm' => 'Password@123',
        ]);

        $response = $this->withSession(session()->get())->post('/signup', $payload);
        $response->assertRedirectTo('/');

        $user = model(UserModel::class)->findByCredentials(['email' => 'newuser@local.test']);

        $this->assertNotNull($user);
        $this->assertTrue($user->inGroup('employee'));
    }

    public function testSignupFailsForDuplicateEmail(): void
    {
        $payload = $this->csrfPayload([
            'username'         => 'duplicate',
            'email'            => 'admin@local.test',
            'password'         => 'Password@123',
            'password_confirm' => 'Password@123',
        ]);

        $response = $this->withSession(session()->get())->post('/signup', $payload);
        $response->assertRedirect();
    }

    private function csrfPayload(array $data): array
    {
        $hash = csrf_hash();
        $name = csrf_token();

        return $data + [$name => $hash];
    }
}
