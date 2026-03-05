<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Inventory\InventoryStockModel;
use App\Models\Inventory\StockMovementModel;
use App\Models\Procurement\ApprovalModel;
use App\Models\Procurement\PoRequestModel;
use App\Models\Procurement\PurchaseOrderItemModel;
use App\Models\Procurement\PurchaseOrderModel;
use App\Models\Procurement\PurchaseRequestModel;
use App\Models\Receiving\ReceivingModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ReceivingWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testApprovedPoRequestCanBeConvertedAndPostedToInventory(): void
    {
        $context = $this->buildPendingPoRequestContext();

        $this->withSession(session()->get())->post(
            '/procurement/po-requests/' . $context['po_request_id'] . '/approve',
            $this->csrfPayload([]),
        );

        $conversionPage = $this->withSession(session()->get())->get('/receiving/create/from-po-request/' . $context['po_request_id']);
        $conversionPage->assertOK();

        /** @var PurchaseOrderItemModel $poItemModel */
        $poItemModel = model(PurchaseOrderItemModel::class);
        $poItems     = $poItemModel->where('purchase_order_id', $context['purchase_order_id'])->findAll();

        $this->assertNotEmpty($poItems);

        $receivingPayload = $this->csrfPayload([
            'po_request_id'          => $context['po_request_id'],
            'received_date'          => '2026-02-20',
            'delivery_reference'     => 'DR-1001',
            'remarks'                => 'Delivered complete',
            'purchase_order_item_id' => array_column($poItems, 'id'),
            'item_name'              => array_column($poItems, 'item_name'),
            'unit'                   => array_column($poItems, 'unit'),
            'received_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'accepted_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'rejected_qty'           => array_fill(0, count($poItems), '0'),
            'batch_no'               => array_fill(0, count($poItems), 'BATCH-001'),
            'lot_no'                 => array_fill(0, count($poItems), 'LOT-001'),
            'expiry_date'            => array_fill(0, count($poItems), '2027-12-31'),
            'unit_cost'              => array_map(static fn (array $item): string => (string) $item['unit_cost'], $poItems),
            'item_remarks'           => array_fill(0, count($poItems), 'OK'),
        ]);

        $createReceiving = $this->withSession(session()->get())->post('/receiving', $receivingPayload);
        $createReceiving->assertRedirect();

        /** @var ReceivingModel $receivingModel */
        $receivingModel = model(ReceivingModel::class);
        $receiving      = $receivingModel->where('po_request_id', $context['po_request_id'])->first();

        $this->assertNotNull($receiving);
        $receivingId = (int) $receiving['id'];

        $postReceiving = $this->withSession(session()->get())->post('/receiving/' . $receivingId . '/post', $this->csrfPayload([]));
        $postReceiving->assertRedirectTo('/receiving/' . $receivingId);

        $postedReceiving = $receivingModel->find($receivingId);
        $this->assertSame('posted', $postedReceiving['status']);

        /** @var PoRequestModel $poRequestModel */
        $poRequestModel = model(PoRequestModel::class);
        $poRequest      = $poRequestModel->find($context['po_request_id']);
        $this->assertSame('converted_to_receiving', $poRequest['status']);

        /** @var PurchaseOrderModel $purchaseOrderModel */
        $purchaseOrderModel = model(PurchaseOrderModel::class);
        $purchaseOrder      = $purchaseOrderModel->find($context['purchase_order_id']);
        $this->assertSame('fully_received', $purchaseOrder['status']);

        /** @var InventoryStockModel $inventoryStockModel */
        $inventoryStockModel = model(InventoryStockModel::class);
        $stocks              = $inventoryStockModel->findAll();
        $this->assertNotEmpty($stocks);

        /** @var StockMovementModel $movementModel */
        $movementModel = model(StockMovementModel::class);
        $movements     = $movementModel
            ->where('reference_type', 'receiving')
            ->where('reference_id', $receivingId)
            ->findAll();

        $this->assertNotEmpty($movements);
        $this->assertGreaterThan(0, (float) $movements[0]['qty_in']);
    }

    public function testNonApprovedPoRequestCannotBeConverted(): void
    {
        $context = $this->buildPendingPoRequestContext();

        $response = $this->withSession(session()->get())->get('/receiving/create/from-po-request/' . $context['po_request_id']);
        $response->assertRedirectTo('/receiving');
    }

    public function testReceivingIndexHidesConversionLinkAfterDraftAlreadyExists(): void
    {
        $context = $this->buildPendingPoRequestContext();

        $this->withSession(session()->get())->post(
            '/procurement/po-requests/' . $context['po_request_id'] . '/approve',
            $this->csrfPayload([]),
        );

        $before = $this->withSession(session()->get())->get('/receiving');
        $before->assertOK();
        $before->assertSee('/receiving/create/from-po-request/' . $context['po_request_id']);

        /** @var PurchaseOrderItemModel $poItemModel */
        $poItemModel = model(PurchaseOrderItemModel::class);
        $poItems     = $poItemModel->where('purchase_order_id', $context['purchase_order_id'])->findAll();
        $this->assertNotEmpty($poItems);

        $createReceiving = $this->withSession(session()->get())->post('/receiving', $this->csrfPayload([
            'po_request_id'          => $context['po_request_id'],
            'received_date'          => '2026-02-20',
            'delivery_reference'     => 'DR-2002',
            'remarks'                => 'Draft receiving created',
            'purchase_order_item_id' => array_column($poItems, 'id'),
            'item_name'              => array_column($poItems, 'item_name'),
            'unit'                   => array_column($poItems, 'unit'),
            'received_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'accepted_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'rejected_qty'           => array_fill(0, count($poItems), '0'),
            'batch_no'               => array_fill(0, count($poItems), 'BATCH-QUEUE'),
            'lot_no'                 => array_fill(0, count($poItems), 'LOT-QUEUE'),
            'expiry_date'            => array_fill(0, count($poItems), '2027-12-31'),
            'unit_cost'              => array_map(static fn (array $item): string => (string) $item['unit_cost'], $poItems),
            'item_remarks'           => array_fill(0, count($poItems), 'queue'),
        ]));
        $createReceiving->assertRedirect();

        $after = $this->withSession(session()->get())->get('/receiving');
        $after->assertOK();
        $after->assertDontSee('/receiving/create/from-po-request/' . $context['po_request_id']);
    }

    public function testCreateReceivingRejectsPastExpiryDate(): void
    {
        $context = $this->buildPendingPoRequestContext();

        $this->withSession(session()->get())->post(
            '/procurement/po-requests/' . $context['po_request_id'] . '/approve',
            $this->csrfPayload([]),
        );

        /** @var PurchaseOrderItemModel $poItemModel */
        $poItemModel = model(PurchaseOrderItemModel::class);
        $poItems     = $poItemModel->where('purchase_order_id', $context['purchase_order_id'])->findAll();
        $this->assertNotEmpty($poItems);

        $response = $this->withSession(session()->get())->post('/receiving', $this->csrfPayload([
            'po_request_id'          => $context['po_request_id'],
            'received_date'          => '2026-02-20',
            'delivery_reference'     => 'DR-PAST',
            'remarks'                => 'Past expiry should fail',
            'purchase_order_item_id' => array_column($poItems, 'id'),
            'item_name'              => array_column($poItems, 'item_name'),
            'unit'                   => array_column($poItems, 'unit'),
            'received_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'accepted_qty'           => array_map(static fn (array $item): string => (string) $item['ordered_qty'], $poItems),
            'rejected_qty'           => array_fill(0, count($poItems), '0'),
            'batch_no'               => array_fill(0, count($poItems), 'BATCH-OLD'),
            'lot_no'                 => array_fill(0, count($poItems), 'LOT-OLD'),
            'expiry_date'            => array_fill(0, count($poItems), '2020-01-01'),
            'unit_cost'              => array_map(static fn (array $item): string => (string) $item['unit_cost'], $poItems),
            'item_remarks'           => array_fill(0, count($poItems), 'old'),
        ]));
        $response->assertRedirect();

        /** @var ReceivingModel $receivingModel */
        $receivingModel = model(ReceivingModel::class);
        $receiving      = $receivingModel->where('po_request_id', $context['po_request_id'])->first();

        $this->assertNull($receiving);
    }

    /**
     * @return array{po_request_id: int, purchase_order_id: int}
     */
    private function buildPendingPoRequestContext(): array
    {
        $employee = $this->findUserByEmail('employee@local.test');
        auth('session')->login($employee);

        $this->withSession(session()->get())->post('/procurement/purchase-requests', $this->csrfPayload([
            'request_date'         => '2026-02-20',
            'needed_date'          => '2026-02-25',
            'remarks'              => 'Stock replenishment',
            'item_name'            => ['Amoxicillin', ''],
            'requested_qty'        => ['5', ''],
            'unit'                 => ['box', ''],
            'estimated_unit_cost'  => ['120', ''],
            'notes'                => ['Urgent', ''],
        ]));

        /** @var PurchaseRequestModel $purchaseRequestModel */
        $purchaseRequestModel = model(PurchaseRequestModel::class);
        $purchaseRequest      = $purchaseRequestModel
            ->where('requested_by', (int) $employee->id)
            ->orderBy('id', 'DESC')
            ->first();

        $purchaseRequestId = (int) $purchaseRequest['id'];

        $this->withSession(session()->get())->post(
            '/procurement/purchase-requests/' . $purchaseRequestId . '/submit',
            $this->csrfPayload([]),
        );

        auth('session')->logout();
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        /** @var ApprovalModel $approvalModel */
        $approvalModel = model(ApprovalModel::class);
        $approval      = $approvalModel
            ->where('reference_type', 'purchase_request')
            ->where('reference_id', $purchaseRequestId)
            ->first();

        $this->withSession(session()->get())->post(
            '/procurement/approvals/' . $approval['id'] . '/approve',
            $this->csrfPayload(['comments' => 'Approved']),
        );

        $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/from-pr/' . $purchaseRequestId,
            $this->csrfPayload(['supplier_name' => 'Supplier B']),
        );

        /** @var PurchaseOrderModel $purchaseOrderModel */
        $purchaseOrderModel = model(PurchaseOrderModel::class);
        $purchaseOrder      = $purchaseOrderModel->where('purchase_request_id', $purchaseRequestId)->first();
        $purchaseOrderId    = (int) $purchaseOrder['id'];

        $this->withSession(session()->get())->post(
            '/procurement/purchase-orders/' . $purchaseOrderId . '/issue',
            $this->csrfPayload([]),
        );

        $this->withSession(session()->get())->post(
            '/procurement/po-requests/from-po/' . $purchaseOrderId,
            $this->csrfPayload([]),
        );

        /** @var PoRequestModel $poRequestModel */
        $poRequestModel = model(PoRequestModel::class);
        $poRequest      = $poRequestModel->where('purchase_order_id', $purchaseOrderId)->first();

        return [
            'po_request_id'    => (int) $poRequest['id'],
            'purchase_order_id' => $purchaseOrderId,
        ];
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
