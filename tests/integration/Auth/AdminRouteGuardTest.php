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
final class AdminRouteGuardTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testGuestIsRedirectedFromAdminDashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirectTo('/login');
    }

    public function testAdminCanAccessAdminDashboard(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->get('/admin/dashboard');
        $response->assertOK();
    }

    public function testEmployeeIsForbiddenFromAdminDashboard(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function testAdminCanAccessCatalogPages(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $this->withSession(session()->get())->get('/admin/products')->assertOK();
        $this->withSession(session()->get())->get('/admin/suppliers')->assertOK();
    }

    public function testEmployeeIsForbiddenFromCatalogPages(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $this->withSession(session()->get())->get('/admin/products')->assertStatus(403);
        $this->withSession(session()->get())->get('/admin/suppliers')->assertStatus(403);
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new \RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }
}
