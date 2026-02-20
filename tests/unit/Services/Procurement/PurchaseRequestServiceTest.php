<?php

use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Services\Procurement\PurchaseRequestService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PurchaseRequestServiceTest extends CIUnitTestCase
{
    public function testCreateDraftStoresItemsAndReturnsId(): void
    {
        $requests  = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $requests->method('findByNumber')->willReturn(null);

        $requests->expects($this->once())
            ->method('create')
            ->willReturn(42);

        $requests->expects($this->once())
            ->method('addItems')
            ->with(
                42,
                $this->callback(static fn (array $items): bool => count($items) === 1 && $items[0]['item_name'] === 'Paracetamol'),
            );

        $service = new PurchaseRequestService($requests, $approvals);

        $purchaseRequestId = $service->create([
            'requested_by' => 10,
            'request_date' => '2026-02-20',
            'items'        => [
                [
                    'item_name'           => 'Paracetamol',
                    'requested_qty'       => '12',
                    'unit'                => 'box',
                    'estimated_unit_cost' => '50',
                ],
            ],
        ]);

        $this->assertSame(42, $purchaseRequestId);
    }

    public function testSubmitCreatesPendingApprovalWhenMissing(): void
    {
        $requests  = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $requests->method('find')->with(5)->willReturn([
            'id'     => 5,
            'status' => 'draft',
        ]);
        $requests->method('listItems')->with(5)->willReturn([
            ['id' => 100, 'item_name' => 'Bandage', 'requested_qty' => 2],
        ]);
        $requests->expects($this->once())
            ->method('update')
            ->with(5, $this->arrayHasKey('status'));

        $approvals->expects($this->once())
            ->method('findPendingByReference')
            ->with('purchase_request', 5)
            ->willReturn(null);
        $approvals->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('reference_type'));

        $service = new PurchaseRequestService($requests, $approvals);
        $service->submit(5);

        $this->assertTrue(true);
    }

    public function testSubmitRejectsNonDraftPurchaseRequest(): void
    {
        $requests  = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $requests->method('find')->with(7)->willReturn([
            'id'     => 7,
            'status' => 'submitted',
        ]);

        $service = new PurchaseRequestService($requests, $approvals);

        $this->expectException(DomainException::class);
        $service->submit(7);
    }
}
