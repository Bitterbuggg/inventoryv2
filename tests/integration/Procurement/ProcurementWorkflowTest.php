<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Procurement\ApprovalModel;
use App\Models\Procurement\PoRequestModel;
use App\Models\Procurement\PurchaseOrderModel;
use App\Models\Procurement\PurchaseRequestModel;
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
