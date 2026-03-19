<?php

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\ReceivingService;
use App\Services\Receiving\ReceivingValidationService;
use App\Services\Shared\AuditService;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReceivingServiceTest extends CIUnitTestCase
{
    public function testCreateDraftSucceedsForApprovedPoRequest(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items      = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders     = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $posting    = $this->createMock(InventoryPostingService::class);
        $db         = $this->createMock(BaseConnection::class);
        $audit      = $this->createMock(AuditService::class);

        $poRequests->method('find')->with(21)->willReturn([
            'id'                => 21,
            'status'            => 'approved',
            'purchase_order_id' => 31,
        ]);

        $receivings->method('findByPoRequest')->with(21)->willReturn(null);

        $orders->method('find')->with(31)->willReturn([
            'id'            => 31,
            'supplier_name' => 'Supplier A',
        ]);

        $orders->method('listItems')->with(31)->willReturn([
            [
                'id'           => 111,
                'ordered_qty'  => 10,
                'received_qty' => 0,
            ],
        ]);

        $receivings->method('findByNumber')->willReturn(null);
        $receivings->expects($this->once())->method('create')->willReturn(99);

        $items->expects($this->once())
            ->method('addItems')
            ->with(99, $this->callback(static fn (array $rows): bool => count($rows) === 1));

        $service = new ReceivingService(
            $receivings,
            $items,
            $poRequests,
            $orders,
            new ReceivingValidationService(),
            $posting,
            $db,
            $audit,
        );

        $receivingId = $service->createDraft([
            'po_request_id' => 21,
            'received_date' => '2026-02-20',
            'received_by'   => 1,
            'items'         => [[
                'purchase_order_item_id' => 111,
                'item_name'              => 'Paracetamol',
                'unit'                   => 'box',
                'received_qty'           => 5,
                'accepted_qty'           => 5,
                'rejected_qty'           => 0,
                'unit_cost'              => 10,
            ]],
        ]);

        $this->assertSame(99, $receivingId);
    }

    public function testCreateDraftRejectsNonApprovedPoRequest(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items      = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders     = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $posting    = $this->createMock(InventoryPostingService::class);
        $db         = $this->createMock(BaseConnection::class);
        $audit      = $this->createMock(AuditService::class);

        $poRequests->method('find')->with(22)->willReturn([
            'id'                => 22,
            'status'            => 'pending',
            'purchase_order_id' => 44,
        ]);

        $service = new ReceivingService(
            $receivings,
            $items,
            $poRequests,
            $orders,
            new ReceivingValidationService(),
            $posting,
            $db,
            $audit,
        );

        $this->expectException(DomainException::class);

        $service->createDraft([
            'po_request_id' => 22,
            'received_date' => '2026-02-20',
            'received_by'   => 1,
            'items'         => [],
        ]);
    }

    public function testPostRollsBackTransactionWhenInventoryPostingFails(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items      = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders     = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $posting    = $this->createMock(InventoryPostingService::class);
        $db         = $this->createMock(BaseConnection::class);
        $audit      = $this->createMock(AuditService::class);

        $receivings->method('find')->with(55)->willReturn([
            'id'                => 55,
            'status'            => 'draft',
            'po_request_id'     => 77,
            'purchase_order_id' => 88,
        ]);

        $poRequests->method('find')->with(77)->willReturn([
            'id'     => 77,
            'status' => 'converting',
        ]);

        $items->method('listByReceiving')->with(55)->willReturn([
            [
                'id'                    => 500,
                'purchase_order_item_id' => 901,
                'accepted_qty'          => 4,
                'received_qty'          => 4,
                'rejected_qty'          => 0,
                'unit_cost'             => 10,
            ],
        ]);

        $orders->method('listItems')->with(88)->willReturn([
            [
                'id'           => 901,
                'ordered_qty'  => 10,
                'received_qty' => 0,
            ],
        ]);

        $items->expects($this->once())
            ->method('update')
            ->with(500, ['line_total' => 40.0]);

        $posting->expects($this->once())
            ->method('postReceivingItems')
            ->with(55, $this->isType('array'), 9)
            ->willThrowException(new RuntimeException('inventory posting failed'));

        $db->expects($this->once())->method('transBegin');
        $db->expects($this->once())->method('transRollback');
        $db->expects($this->never())->method('transCommit');

        $service = new ReceivingService(
            $receivings,
            $items,
            $poRequests,
            $orders,
            new ReceivingValidationService(),
            $posting,
            $db,
            $audit,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('inventory posting failed');

        $service->post(55, 9);
    }

    public function testListConvertiblePoRequestsExcludesPoRequestsWithActiveReceiving(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items      = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders     = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $posting    = $this->createMock(InventoryPostingService::class);
        $db         = $this->createMock(BaseConnection::class);
        $audit      = $this->createMock(AuditService::class);

        $poRequests->method('list')
            ->with(['status' => 'approved'])
            ->willReturn([
                ['id' => 201, 'status' => 'approved'],
                ['id' => 202, 'status' => 'approved'],
                ['id' => 203, 'status' => 'approved'],
            ]);

        $receivings->method('findByPoRequest')
            ->willReturnMap([
                [201, ['id' => 1, 'status' => 'draft']],
                [202, ['id' => 2, 'status' => 'voided']],
                [203, null],
            ]);

        $service = new ReceivingService(
            $receivings,
            $items,
            $poRequests,
            $orders,
            new ReceivingValidationService(),
            $posting,
            $db,
            $audit,
        );

        $result = $service->listConvertiblePoRequests();
        $ids = array_map(static fn (array $row): int => (int) ($row['id'] ?? 0), $result);

        $this->assertSame([202, 203], $ids);
    }
}
