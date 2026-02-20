<?php

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\ReceivingService;
use App\Services\Receiving\ReceivingValidationService;
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
        );

        $this->expectException(DomainException::class);

        $service->createDraft([
            'po_request_id' => 22,
            'received_date' => '2026-02-20',
            'received_by'   => 1,
            'items'         => [],
        ]);
    }
}
