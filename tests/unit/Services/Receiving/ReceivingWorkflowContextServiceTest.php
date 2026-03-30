<?php

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Services\Receiving\ReceivingWorkflowContextService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReceivingWorkflowContextServiceTest extends CIUnitTestCase
{
    public function testBuildConversionContextReturnsRemainingPurchaseOrderItems(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders = $this->createMock(PurchaseOrderRepositoryInterface::class);

        $poRequests->method('find')->with(21)->willReturn([
            'id' => 21,
            'status' => 'approved',
            'purchase_order_id' => 31,
        ]);
        $receivings->method('findByPoRequest')->with(21)->willReturn(null);
        $orders->method('find')->with(31)->willReturn([
            'id' => 31,
            'supplier_name' => 'Supplier A',
        ]);
        $orders->method('listItems')->with(31)->willReturn([
            ['id' => 111, 'ordered_qty' => 10, 'received_qty' => 4],
            ['id' => 112, 'ordered_qty' => 5, 'received_qty' => 5],
        ]);

        $service = new ReceivingWorkflowContextService($receivings, $items, $poRequests, $orders);

        $context = $service->buildConversionContext(21);

        $this->assertSame(21, $context['po_request']['id']);
        $this->assertCount(1, $context['remaining_items']);
        $this->assertSame(111, $context['remaining_items'][0]['id']);
        $this->assertArrayHasKey(111, $context['purchase_order_items_by_id']);
    }

    public function testBuildConversionContextRejectsNonApprovedPoRequest(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders = $this->createMock(PurchaseOrderRepositoryInterface::class);

        $poRequests->method('find')->with(22)->willReturn([
            'id' => 22,
            'status' => 'pending',
            'purchase_order_id' => 40,
        ]);

        $service = new ReceivingWorkflowContextService($receivings, $items, $poRequests, $orders);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Only approved PO requests can be converted to receiving.');

        $service->buildConversionContext(22);
    }

    public function testBuildDraftContextLoadsReceivingItemsAndPurchaseOrderMap(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders = $this->createMock(PurchaseOrderRepositoryInterface::class);

        $receivings->method('find')->with(55)->willReturn([
            'id' => 55,
            'purchase_order_id' => 88,
        ]);
        $items->method('listByReceiving')->with(55)->willReturn([
            ['id' => 500, 'purchase_order_item_id' => 901, 'accepted_qty' => 4],
        ]);
        $orders->method('find')->with(88)->willReturn(['id' => 88]);
        $orders->method('listItems')->with(88)->willReturn([
            ['id' => 901, 'ordered_qty' => 10, 'received_qty' => 0],
        ]);

        $service = new ReceivingWorkflowContextService($receivings, $items, $poRequests, $orders);

        $context = $service->buildDraftContext(55);

        $this->assertCount(1, $context['items']);
        $this->assertArrayHasKey(901, $context['purchase_order_items_by_id']);
    }

    public function testBuildPostingContextRequiresConvertingPoRequestAndItems(): void
    {
        $receivings = $this->createMock(ReceivingRepositoryInterface::class);
        $items = $this->createMock(ReceivingItemRepositoryInterface::class);
        $poRequests = $this->createMock(PoRequestRepositoryInterface::class);
        $orders = $this->createMock(PurchaseOrderRepositoryInterface::class);

        $receivings->method('find')->with(56)->willReturn([
            'id' => 56,
            'status' => 'draft',
            'po_request_id' => 77,
            'purchase_order_id' => 89,
        ]);
        $items->method('listByReceiving')->with(56)->willReturn([]);
        $orders->method('find')->with(89)->willReturn(['id' => 89]);
        $orders->method('listItems')->with(89)->willReturn([
            ['id' => 902, 'ordered_qty' => 10, 'received_qty' => 0],
        ]);
        $poRequests->method('find')->with(77)->willReturn([
            'id' => 77,
            'status' => 'converting',
        ]);

        $service = new ReceivingWorkflowContextService($receivings, $items, $poRequests, $orders);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Receiving has no items to post.');

        $service->buildPostingContext(56);
    }
}
