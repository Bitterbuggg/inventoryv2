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
final class ProcurementRouteGuardTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testGuestCannotAccessPurchaseRequestsPage(): void
    {
        auth('session')->logout();
        session()->remove('user');
        session()->destroy();

        $response = $this->get('/procurement/purchase-requests');
        $status   = $response->response()->getStatusCode();

        if (in_array($status, [301, 302, 303, 307, 308], true)) {
            $response->assertRedirectTo('/login');

            return;
        }

        $response->assertStatus(403);
    }

    public function testEmployeeCannotAccessPendingApprovalsPage(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/procurement/approvals/pending');
        $response->assertStatus(403);
    }

    public function testEmployeeCanAccessPurchaseRequestsPage(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/procurement/purchase-requests');
        $response->assertOK();
    }

    public function testItStaffCanAccessPendingApprovalsPage(): void
    {
        $itStaff = $this->findUserByEmail('itstaff@local.test');
        auth('session')->login($itStaff);

        $response = $this->withSession(session()->get())->get('/procurement/approvals/pending');
        $response->assertOK();
    }

    public function testEmployeeCannotAccessPurchaseOrdersPageWithoutPermission(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/procurement/purchase-orders');
        $response->assertStatus(403);
    }

    public function testAdminCanAccessPoRequestsPage(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->get('/procurement/po-requests');
        $response->assertOK();
    }

    public function testEmployeeWithPurchaseOrderPermissionCanAccessPurchaseOrdersPage(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        $employee->addPermission('procurement.po.create');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->get('/procurement/purchase-orders');
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

