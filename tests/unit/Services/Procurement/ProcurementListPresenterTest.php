<?php

use App\Services\Procurement\ApprovalService;
use App\Services\Procurement\PoRequestService;
use App\Services\Procurement\ProcurementListPresenter;
use App\Services\Procurement\PurchaseOrderService;
use App\Services\Procurement\PurchaseRequestService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProcurementListPresenterTest extends CIUnitTestCase
{
    public function testListPendingApprovalsEnrichesPurchaseRequestDetails(): void
    {
        $purchaseRequests = $this->createMock(PurchaseRequestService::class);
        $approvals = $this->createMock(ApprovalService::class);
        $purchaseOrders = $this->createMock(PurchaseOrderService::class);
        $poRequests = $this->createMock(PoRequestService::class);

        $approvals->expects($this->once())
            ->method('listPending')
            ->willReturn([
                [
                    'id' => 7,
                    'reference_type' => 'purchase_request',
                    'reference_id' => 15,
                ],
            ]);

        $purchaseRequests->expects($this->once())
            ->method('findWithItems')
            ->with(15)
            ->willReturn([
                'pr_number' => 'PR-001',
                'request_date' => '2026-03-30',
                'requested_by' => 'Employee',
                'remarks' => 'Urgent',
                'items' => [['item_name' => 'Bandage']],
            ]);

        $presenter = new ProcurementListPresenter($purchaseRequests, $approvals, $purchaseOrders, $poRequests);

        $result = $presenter->listPendingApprovals();

        $this->assertSame('PR-001', $result[0]['purchase_request']['pr_number']);
        $this->assertCount(1, $result[0]['purchase_request']['items']);
    }

    public function testListPurchaseOrdersDecoratesStatusAndPoRequestBadge(): void
    {
        $purchaseRequests = $this->createMock(PurchaseRequestService::class);
        $approvals = $this->createMock(ApprovalService::class);
        $purchaseOrders = $this->createMock(PurchaseOrderService::class);
        $poRequests = $this->createMock(PoRequestService::class);

        $purchaseOrders->expects($this->once())
            ->method('listForIndex')
            ->with('issued')
            ->willReturn([
                [
                    'id' => 10,
                    'status' => 'partially_received',
                    'po_request_status' => 'approved',
                    'has_open_po_request' => true,
                ],
            ]);

        $presenter = new ProcurementListPresenter($purchaseRequests, $approvals, $purchaseOrders, $poRequests);

        $result = $presenter->listPurchaseOrders('issued');

        $this->assertSame('Partially Received', $result[0]['status_label']);
        $this->assertSame('status-partial', $result[0]['status_badge_class']);
        $this->assertSame('action-badge-success', $result[0]['po_request_badge_class']);
        $this->assertSame('PO REQ: APPROVED', $result[0]['po_request_badge_label']);
    }

    public function testListPoRequestsDecoratesStatusOrderDetailsAndActorLabel(): void
    {
        $purchaseRequests = $this->createMock(PurchaseRequestService::class);
        $approvals = $this->createMock(ApprovalService::class);
        $purchaseOrders = $this->createMock(PurchaseOrderService::class);
        $poRequests = $this->createMock(PoRequestService::class);

        $poRequests->expects($this->once())
            ->method('list')
            ->with('approved')
            ->willReturn([
                [
                    'id' => 31,
                    'purchase_order_id' => 9,
                    'status' => 'approved',
                    'approved_by' => 4,
                ],
            ]);

        $purchaseOrders->expects($this->once())
            ->method('findWithItems')
            ->with(9)
            ->willReturn([
                'po_number' => 'PO-001',
                'supplier_name' => 'Supplier A',
                'order_date' => '2026-03-30',
                'total_amount' => 1500,
                'items' => [['item_name' => 'Bandage']],
            ]);

        $presenter = new ProcurementListPresenter($purchaseRequests, $approvals, $purchaseOrders, $poRequests);

        $result = $presenter->listPoRequests('approved');

        $this->assertSame('Approved', $result[0]['status_label']);
        $this->assertFalse($result[0]['uses_special_status_badge']);
        $this->assertSame('4', $result[0]['action_by_label']);
        $this->assertSame('PO-001', $result[0]['purchase_order']['po_number']);
    }
}
