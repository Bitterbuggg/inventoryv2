<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Procurement\ApprovalModel;
use App\Models\Procurement\PoRequestModel;
use App\Models\Procurement\PurchaseOrderModel;
use App\Models\Procurement\PurchaseRequestModel;
use App\Models\Procurement\PurchaseRequestItemModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ProcurementWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testEndToEndProcurementFlowFromPrToApprovedPoRequest(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $createPrResponse = $this->withSession(session()->get())->post('/procurement/purchase-requests', $this->csrfPayload([
            'request_date'         => '2026-02-20',
            'needed_date'          => '2026-02-25',
            'remarks'              => 'Initial stock replenishment',
            'item_name'            => ['Paracetamol 500mg', ''],
            'requested_qty'        => ['10', ''],
            'unit'                 => ['box', ''],
            'estimated_unit_cost'  => ['75.50', ''],
            'notes'                => ['Urgent', ''],
        ]));
        $createPrResponse->assertRedirectTo('/procurement/purchase-requests');

        /** @var PurchaseRequestModel $purchaseRequestModel */
        $purchaseRequestModel = model(PurchaseRequestModel::class);
        $purchaseRequest = $purchaseRequestModel
            ->where('requested_by', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($purchaseRequest);
        $purchaseRequestId = (int) $purchaseRequest['id'];

        $submitPrResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-requests/' . $purchaseRequestId . '/submit',
            $this->csrfPayload([]),
        );
        $submitPrResponse->assertRedirectTo('/procurement/purchase-requests');

        $submittedPr = $purchaseRequestModel->find($purchaseRequestId);
        $this->assertSame('submitted', $submittedPr['status']);

        /** @var ApprovalModel $approvalModel */
        $approvalModel = model(ApprovalModel::class);
        $approval      = $approvalModel
            ->where('reference_type', 'purchase_request')
            ->where('reference_id', $purchaseRequestId)
            ->first();

        $this->assertNotNull($approval);

        auth('session')->logout();
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $approvePrResponse = $this->withSession(session()->get())->post(
            '/procurement/approvals/' . $approval['id'] . '/approve',
            $this->csrfPayload(['comments' => 'Approved for procurement']),
        );
        $approvePrResponse->assertRedirectTo('/procurement/approvals/pending');

        $approvedPr = $purchaseRequestModel->find($purchaseRequestId);
        $this->assertSame('approved', $approvedPr['status']);

        $createPoResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/from-pr/' . $purchaseRequestId,
            $this->csrfPayload(['supplier_name' => 'ACME Pharma Supply']),
        );
        $createPoResponse->assertRedirectTo('/procurement/purchase-orders');

        /** @var PurchaseOrderModel $purchaseOrderModel */
        $purchaseOrderModel = model(PurchaseOrderModel::class);
        $purchaseOrder = $purchaseOrderModel->where('purchase_request_id', $purchaseRequestId)->first();

        $this->assertNotNull($purchaseOrder);
        $purchaseOrderId = (int) $purchaseOrder['id'];

        $issuePoResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/' . $purchaseOrderId . '/issue',
            $this->csrfPayload([]),
        );
        $issuePoResponse->assertRedirectTo('/procurement/purchase-orders');

        $issuedPo = $purchaseOrderModel->find($purchaseOrderId);
        $this->assertSame('issued', $issuedPo['status']);

        $createPoRequestResponse = $this->withSession(session()->get())->post(
            '/procurement/po-requests/from-po/' . $purchaseOrderId,
            $this->csrfPayload([]),
        );
        $createPoRequestResponse->assertRedirectTo('/procurement/po-requests');

        /** @var PoRequestModel $poRequestModel */
        $poRequestModel = model(PoRequestModel::class);
        $poRequest = $poRequestModel->where('purchase_order_id', $purchaseOrderId)->first();

        $this->assertNotNull($poRequest);
        $poRequestId = (int) $poRequest['id'];

        $approvePoRequestResponse = $this->withSession(session()->get())->post(
            '/procurement/po-requests/' . $poRequestId . '/approve',
            $this->csrfPayload([]),
        );
        $approvePoRequestResponse->assertRedirectTo('/procurement/po-requests');

        $approvedPoRequest = $poRequestModel->find($poRequestId);
        $this->assertSame('approved', $approvedPoRequest['status']);
    }

    public function testDraftPurchaseRequestCanBeEdited(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $createPrResponse = $this->withSession(session()->get())->post('/procurement/purchase-requests', $this->csrfPayload([
            'request_date'         => '2026-02-20',
            'needed_date'          => '2026-02-25',
            'remarks'              => 'Initial draft',
            'item_name'            => ['Vitamin C'],
            'requested_qty'        => ['10'],
            'unit'                 => ['box'],
            'estimated_unit_cost'  => ['65.00'],
            'notes'                => ['Initial line'],
        ]));
        $createPrResponse->assertRedirectTo('/procurement/purchase-requests');

        /** @var PurchaseRequestModel $purchaseRequestModel */
        $purchaseRequestModel = model(PurchaseRequestModel::class);
        $purchaseRequest = $purchaseRequestModel
            ->where('requested_by', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($purchaseRequest);
        $purchaseRequestId = (int) $purchaseRequest['id'];

        $editPage = $this->withSession(session()->get())->get('/procurement/purchase-requests/' . $purchaseRequestId . '/edit');
        $editPage->assertOK();

        $updateResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-requests/' . $purchaseRequestId . '/update',
            $this->csrfPayload([
                'request_date'         => '2026-02-21',
                'needed_date'          => '2026-02-26',
                'remarks'              => 'Updated draft',
                'item_name'            => ['Vitamin C', 'Bandage'],
                'requested_qty'        => ['8', '3'],
                'unit'                 => ['box', 'pack'],
                'estimated_unit_cost'  => ['60.00', '22.50'],
                'notes'                => ['Adjusted qty', 'Add support item'],
            ]),
        );
        $updateResponse->assertRedirectTo('/procurement/purchase-requests');

        $updated = $purchaseRequestModel->find($purchaseRequestId);
        $this->assertSame('2026-02-21', $updated['request_date']);
        $this->assertSame('2026-02-26', $updated['needed_date']);
        $this->assertSame('Updated draft', $updated['remarks']);

        /** @var PurchaseRequestItemModel $itemModel */
        $itemModel = model(PurchaseRequestItemModel::class);
        $items = $itemModel->where('purchase_request_id', $purchaseRequestId)->orderBy('id', 'ASC')->findAll();

        $this->assertCount(2, $items);
        $this->assertSame('Vitamin C', $items[0]['item_name']);
        $this->assertSame('Bandage', $items[1]['item_name']);
    }

    public function testApprovedPurchaseRequestCannotBeConvertedToPurchaseOrderTwice(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $createPrResponse = $this->withSession(session()->get())->post('/procurement/purchase-requests', $this->csrfPayload([
            'request_date'         => '2026-02-20',
            'needed_date'          => '2026-02-25',
            'remarks'              => 'Duplicate PO protection',
            'item_name'            => ['Paracetamol 500mg'],
            'requested_qty'        => ['10'],
            'unit'                 => ['box'],
            'estimated_unit_cost'  => ['75.50'],
            'notes'                => [''],
        ]));
        $createPrResponse->assertRedirectTo('/procurement/purchase-requests');

        /** @var PurchaseRequestModel $purchaseRequestModel */
        $purchaseRequestModel = model(PurchaseRequestModel::class);
        $purchaseRequest = $purchaseRequestModel
            ->where('requested_by', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($purchaseRequest);
        $purchaseRequestId = (int) $purchaseRequest['id'];

        $this->withSession(session()->get())->post(
            '/procurement/purchase-requests/' . $purchaseRequestId . '/submit',
            $this->csrfPayload([]),
        );

        /** @var ApprovalModel $approvalModel */
        $approvalModel = model(ApprovalModel::class);
        $approval = $approvalModel
            ->where('reference_type', 'purchase_request')
            ->where('reference_id', $purchaseRequestId)
            ->first();

        $this->assertNotNull($approval);

        auth('session')->logout();
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $this->withSession(session()->get())->post(
            '/procurement/approvals/' . $approval['id'] . '/approve',
            $this->csrfPayload(['comments' => 'Approved for procurement']),
        );

        $firstCreateResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/from-pr/' . $purchaseRequestId,
            $this->csrfPayload(['supplier_name' => 'ACME Pharma Supply']),
        );
        $firstCreateResponse->assertRedirectTo('/procurement/purchase-orders');

        $secondCreateResponse = $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/from-pr/' . $purchaseRequestId,
            $this->csrfPayload(['supplier_name' => 'ACME Pharma Supply']),
        );
        $this->assertSame(302, $secondCreateResponse->response()->getStatusCode());

        /** @var PurchaseOrderModel $purchaseOrderModel */
        $purchaseOrderModel = model(PurchaseOrderModel::class);
        $purchaseOrders = $purchaseOrderModel->where('purchase_request_id', $purchaseRequestId)->findAll();

        $this->assertCount(1, $purchaseOrders);
        $this->assertSame('A Purchase Order already exists for this request.', session()->getFlashdata('error'));
    }

    public function testCreatePurchaseRequestRejectsDecimalRequestedQty(): void
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $response = $this->withSession(session()->get())->post('/procurement/purchase-requests', $this->csrfPayload([
            'request_date'         => '2026-02-20',
            'needed_date'          => '2026-02-25',
            'remarks'              => 'Decimal qty should fail',
            'item_name'            => ['Paracetamol 500mg'],
            'requested_qty'        => ['1.5'],
            'unit'                 => ['box'],
            'estimated_unit_cost'  => ['75.50'],
            'notes'                => [''],
        ]));
        $response->assertRedirect();

        /** @var PurchaseRequestModel $purchaseRequestModel */
        $purchaseRequestModel = model(PurchaseRequestModel::class);
        $rows = $purchaseRequestModel->where('requested_by', (int) $employee->id)->findAll();

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


