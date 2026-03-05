<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Inventory\InventoryStockModel;
use App\Models\Inventory\IssuanceItemModel;
use App\Models\Inventory\IssuanceModel;
use App\Models\Inventory\StockMovementModel;
use App\Models\Shared\AuditLogModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class IssuanceWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testApprovedIssuanceCanBeReleasedAndDeductsStock(): void
    {
        $stockId  = $this->seedStock('Paracetamol 500mg', 'box', 20, 10);
        $employee = $this->findUserByEmail('employee@local.test');
        $admin    = $this->findUserByEmail('admin@local.test');

        auth('session')->login($employee);

        $createResponse = $this->withSession(session()->get())->post('/inventory/issuance', $this->csrfPayload([
            'issue_date'    => '2026-02-20',
            'department'    => 'Pharmacy',
            'purpose'       => 'Ward replenishment',
            'remarks'       => 'Routine issue',
            'item_name'     => ['Paracetamol 500mg'],
            'unit'          => ['box'],
            'requested_qty' => ['5'],
            'item_remarks'  => [''],
        ]));
        $createResponse->assertRedirect();

        /** @var IssuanceModel $issuanceModel */
        $issuanceModel = model(IssuanceModel::class);
        $issuance      = $issuanceModel
            ->where('requestor_id', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($issuance);
        $issuanceId = (int) $issuance['id'];

        $submitResponse = $this->withSession(session()->get())->post(
            '/inventory/issuance/' . $issuanceId . '/submit',
            $this->csrfPayload([]),
        );
        $submitResponse->assertRedirectTo('/inventory/issuance/' . $issuanceId);

        auth('session')->logout();
        auth('session')->login($admin);

        $approveResponse = $this->withSession(session()->get())->post(
            '/inventory/issuance/' . $issuanceId . '/approve',
            $this->csrfPayload(['comments' => 'Approved']),
        );
        $approveResponse->assertRedirectTo('/inventory/issuance/' . $issuanceId);

        $releaseResponse = $this->withSession(session()->get())->post(
            '/inventory/issuance/' . $issuanceId . '/release',
            $this->csrfPayload([]),
        );
        $releaseResponse->assertRedirectTo('/inventory/issuance/' . $issuanceId);

        $releasedIssuance = $issuanceModel->find($issuanceId);
        $this->assertSame('released', $releasedIssuance['status']);
        $this->assertSame((int) $admin->id, (int) $releasedIssuance['released_by']);

        /** @var IssuanceItemModel $issuanceItemModel */
        $issuanceItemModel = model(IssuanceItemModel::class);
        $item              = $issuanceItemModel->where('issuance_id', $issuanceId)->first();

        $this->assertNotNull($item);
        $this->assertEqualsWithDelta(5.0, (float) $item['issued_qty'], 0.0001);

        /** @var InventoryStockModel $stockModel */
        $stockModel = model(InventoryStockModel::class);
        $stock      = $stockModel->find($stockId);

        $this->assertNotNull($stock);
        $this->assertEqualsWithDelta(15.0, (float) $stock['on_hand_qty'], 0.0001);
        $this->assertEqualsWithDelta(15.0, (float) $stock['available_qty'], 0.0001);

        /** @var StockMovementModel $movementModel */
        $movementModel = model(StockMovementModel::class);
        $movement      = $movementModel
            ->where('reference_type', 'issuance')
            ->where('reference_id', $issuanceId)
            ->first();

        $this->assertNotNull($movement);
        $this->assertSame('issuance', $movement['movement_type']);
        $this->assertEqualsWithDelta(5.0, (float) $movement['qty_out'], 0.0001);

        /** @var AuditLogModel $auditModel */
        $auditModel = model(AuditLogModel::class);
        $auditRows  = $auditModel
            ->where('reference_type', 'issuance')
            ->where('reference_id', $issuanceId)
            ->orderBy('id', 'ASC')
            ->findAll();

        $actions = array_map(static fn (array $row): string => (string) ($row['action'] ?? ''), $auditRows);

        $this->assertContains('issuance.draft_created', $actions);
        $this->assertContains('issuance.submitted', $actions);
        $this->assertContains('issuance.approved', $actions);
        $this->assertContains('issuance.released', $actions);

        $reportResponse = $this->withSession(session()->get())->get('/reports/fast-moving');
        $reportResponse->assertOK();
        $reportResponse->assertSee('Paracetamol 500mg');

        $issuanceReportResponse = $this->withSession(session()->get())->get('/reports/issuances?status=released');
        $issuanceReportResponse->assertOK();
        $issuanceReportResponse->assertSee((string) $releasedIssuance['issuance_number']);

        $lowStockResponse = $this->withSession(session()->get())->get('/reports/low-stock?threshold=20');
        $lowStockResponse->assertOK();
        $lowStockResponse->assertSee('Paracetamol 500mg');
    }

    public function testReleaseFailsWhenStockIsInsufficient(): void
    {
        $stockId  = $this->seedStock('Amoxicillin 500mg', 'box', 2, 12);
        $employee = $this->findUserByEmail('employee@local.test');
        $admin    = $this->findUserByEmail('admin@local.test');

        auth('session')->login($employee);

        $this->withSession(session()->get())->post('/inventory/issuance', $this->csrfPayload([
            'issue_date'    => '2026-02-20',
            'department'    => 'Pharmacy',
            'purpose'       => 'Dispensing',
            'remarks'       => 'Insufficient test',
            'item_name'     => ['Amoxicillin 500mg'],
            'unit'          => ['box'],
            'requested_qty' => ['5'],
            'item_remarks'  => [''],
        ]));

        /** @var IssuanceModel $issuanceModel */
        $issuanceModel = model(IssuanceModel::class);
        $issuance      = $issuanceModel
            ->where('requestor_id', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($issuance);
        $issuanceId = (int) $issuance['id'];

        $this->withSession(session()->get())->post('/inventory/issuance/' . $issuanceId . '/submit', $this->csrfPayload([]));

        auth('session')->logout();
        auth('session')->login($admin);

        $this->withSession(session()->get())->post(
            '/inventory/issuance/' . $issuanceId . '/approve',
            $this->csrfPayload(['comments' => 'Approved']),
        );

        $releaseResponse = $this->withSession(session()->get())->post(
            '/inventory/issuance/' . $issuanceId . '/release',
            $this->csrfPayload([]),
        );
        $releaseResponse->assertRedirect();

        $updatedIssuance = $issuanceModel->find($issuanceId);
        $this->assertSame('approved', $updatedIssuance['status']);

        /** @var InventoryStockModel $stockModel */
        $stockModel = model(InventoryStockModel::class);
        $stock      = $stockModel->find($stockId);

        $this->assertNotNull($stock);
        $this->assertEqualsWithDelta(2.0, (float) $stock['on_hand_qty'], 0.0001);
        $this->assertEqualsWithDelta(2.0, (float) $stock['available_qty'], 0.0001);

        /** @var StockMovementModel $movementModel */
        $movementModel = model(StockMovementModel::class);
        $movements     = $movementModel
            ->where('reference_type', 'issuance')
            ->where('reference_id', $issuanceId)
            ->findAll();

        $this->assertSame([], $movements);

        /** @var AuditLogModel $auditModel */
        $auditModel = model(AuditLogModel::class);
        $failedLogs = $auditModel
            ->where('reference_type', 'issuance')
            ->where('reference_id', $issuanceId)
            ->where('action', 'issuance.release_failed')
            ->findAll();

        $this->assertNotEmpty($failedLogs);
    }

    public function testCreateIssuanceRejectsDecimalRequestedQty(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->post('/inventory/issuance', $this->csrfPayload([
            'issue_date'    => '2026-02-20',
            'department'    => 'Pharmacy',
            'purpose'       => 'Decimal quantity test',
            'remarks'       => 'Should fail',
            'item_name'     => ['Paracetamol 500mg'],
            'unit'          => ['box'],
            'requested_qty' => ['1.5'],
            'item_remarks'  => [''],
        ]));
        $response->assertRedirect();

        /** @var IssuanceModel $issuanceModel */
        $issuanceModel = model(IssuanceModel::class);
        $rows          = $issuanceModel->where('requestor_id', (int) $employee->id)->findAll();

        $this->assertSame([], $rows);
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }

    private function seedStock(string $itemName, string $unit, float $qty, float $unitCost): int
    {
        /** @var InventoryStockModel $stockModel */
        $stockModel = model(InventoryStockModel::class);

        $id = $stockModel->insert([
            'item_name'         => $itemName,
            'unit'              => $unit,
            'batch_no'          => 'BATCH-001',
            'lot_no'            => 'LOT-001',
            'expiry_date'       => '2027-12-31',
            'on_hand_qty'       => $qty,
            'reserved_qty'      => 0,
            'available_qty'     => $qty,
            'average_unit_cost' => $unitCost,
            'last_movement_at'  => date('Y-m-d H:i:s'),
        ], true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Unable to seed inventory stock for test.');
        }

        return (int) $id;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function csrfPayload(array $data): array
    {
        return $data + [csrf_token() => csrf_hash()];
    }
}

