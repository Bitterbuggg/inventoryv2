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
final class AnalyticsRouteGuardTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    protected function tearDown(): void
    {
        try {
            auth('session')->logout();
        } catch (\Throwable) {
            // No active auth context in some runs.
        }

        parent::tearDown();
    }

    public function testGuestIsRedirectedFromAnalyticsDashboard(): void
    {
        $response = $this->get('/analytics/dashboard');
        $response->assertRedirectTo('/login');
    }

    public function testAdminCanAccessAnalyticsDashboard(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->get('/analytics/dashboard');
        $response->assertOK();
    }

    public function testGuestIsRedirectedFromAnalyticsSystemArchitecture(): void
    {
        $response = $this->get('/analytics/system-architecture');
        $response->assertRedirectTo('/login');
    }

    public function testAdminCanAccessAnalyticsSystemArchitecture(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->get('/analytics/system-architecture');
        $response->assertOK();
    }

    public function testItStaffCanAccessAnalyticsSystemArchitecture(): void
    {
        $itStaff = $this->findUserByEmail('itstaff@local.test');
        auth('session')->login($itStaff);

        $response = $this->withSession(session()->get())->get('/analytics/system-architecture');
        $response->assertOK();
    }

    public function testEmployeeIsForbiddenFromAnalyticsSystemArchitecture(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/analytics/system-architecture');
        $response->assertStatus(403);
    }

    public function testEmployeeIsForbiddenFromAnalyticsDashboard(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/analytics/dashboard');
        $response->assertStatus(403);
    }

    public function testEmployeeWithAuditPermissionCanAccessAnalyticsDashboard(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        $employee->addPermission('audit.view');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/analytics/dashboard');
        $response->assertOK();
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }
}
