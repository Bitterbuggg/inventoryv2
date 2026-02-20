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

    public function testCreateRejectsDuplicateItems(): void
    {
        $requests  = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $requests->expects($this->never())->method('create');

        $service = new PurchaseRequestService($requests, $approvals);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Duplicate purchase request items are not allowed.');

        $service->create([
            'requested_by' => 10,
            'request_date' => '2026-02-20',
            'items'        => [
                [
                    'item_name'     => 'Paracetamol',
                    'requested_qty' => '5',
                    'unit'          => 'box',
                ],
                [
                    'item_name'     => 'paracetamol',
                    'requested_qty' => '2',
                    'unit'          => 'BOX',
                ],
            ],
        ]);
    }

    public function testUpdateDraftReplacesItems(): void
    {
        $requests  = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $requests->method('find')->with(15)->willReturn([
            'id'     => 15,
            'status' => 'draft',
        ]);

        $requests->expects($this->once())
            ->method('update')
            ->with(15, $this->callback(static fn (array $data): bool => ($data['request_date'] ?? '') === '2026-02-22'));

        $requests->expects($this->once())
            ->method('replaceItems')
            ->with(
                15,
                $this->callback(static fn (array $items): bool => count($items) === 1 && (float) $items[0]['requested_qty'] === 9.0),
            );

        $service = new PurchaseRequestService($requests, $approvals);

        $service->update(15, [
            'request_date' => '2026-02-22',
            'needed_date'  => '2026-02-24',
            'remarks'      => 'Updated details',
            'items'        => [
                [
                    'item_name'     => 'Bandage',
                    'requested_qty' => '9',
                    'unit'          => 'pack',
                ],
            ],
        ]);

        $this->assertTrue(true);
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
