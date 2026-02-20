<?php

use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Services\Procurement\PurchaseOrderService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PurchaseOrderServiceTest extends CIUnitTestCase
{
    public function testCreateFromApprovedPurchaseRequestCreatesPurchaseOrder(): void
    {
        $orders   = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $requests = $this->createMock(PurchaseRequestRepositoryInterface::class);

        $requests->method('find')->with(11)->willReturn([
            'id'     => 11,
            'status' => 'approved',
        ]);
        $orders->method('findByPurchaseRequest')->with(11)->willReturn(null);
        $requests->method('listItems')->with(11)->willReturn([
            [
                'id'                  => 91,
                'item_name'           => 'Gloves',
                'requested_qty'       => 20,
                'approved_qty'        => 18,
                'unit'                => 'box',
                'estimated_unit_cost' => 12.5,
            ],
        ]);

        $orders->method('findByNumber')->willReturn(null);
        $orders->expects($this->once())
            ->method('create')
            ->willReturn(77);
        $orders->expects($this->once())
            ->method('addItems')
            ->with(77, $this->callback(static fn (array $items): bool => count($items) === 1 && (float) $items[0]['line_total'] > 0));
        $orders->expects($this->once())
            ->method('update')
            ->with(77, $this->arrayHasKey('subtotal_amount'));

        $requests->expects($this->once())
            ->method('update')
            ->with(11, ['status' => 'converted_to_po']);

        $service = new PurchaseOrderService($orders, $requests);
        $id      = $service->createFromPurchaseRequest(11, 'Supplier A');

        $this->assertSame(77, $id);
    }

    public function testCreateFromPurchaseRequestRejectsInvalidStatus(): void
    {
        $orders   = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $requests = $this->createMock(PurchaseRequestRepositoryInterface::class);

        $requests->method('find')->with(12)->willReturn([
            'id'     => 12,
            'status' => 'submitted',
        ]);

        $service = new PurchaseOrderService($orders, $requests);

        $this->expectException(DomainException::class);
        $service->createFromPurchaseRequest(12);
    }

    public function testIssueRequiresDraftStatus(): void
    {
        $orders   = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $requests = $this->createMock(PurchaseRequestRepositoryInterface::class);

        $orders->method('find')->with(15)->willReturn([
            'id'     => 15,
            'status' => 'issued',
        ]);

        $service = new PurchaseOrderService($orders, $requests);

        $this->expectException(DomainException::class);
        $service->issue(15, 1);
    }
}
