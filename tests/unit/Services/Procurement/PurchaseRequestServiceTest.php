<?php

use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Services\Catalog\ProductService;
use App\Services\Procurement\PurchaseRequestService;
use App\Services\Shared\ApprovalWorkflowService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PurchaseRequestServiceTest extends CIUnitTestCase
{
    public function testCreateDraftStoresItemsAndReturnsId(): void
    {
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

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

        $service = new PurchaseRequestService($requests, $approvalWorkflow);

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
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $requests->expects($this->never())->method('create');

        $service = new PurchaseRequestService($requests, $approvalWorkflow);

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
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

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

        $service = new PurchaseRequestService($requests, $approvalWorkflow);

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
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

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

        $approvalWorkflow->expects($this->once())
            ->method('ensurePendingApproval')
            ->with('purchase_request', 5)
            ->willReturn(77);

        $service = new PurchaseRequestService($requests, $approvalWorkflow);
        $service->submit(5);

        $this->assertTrue(true);
    }

    public function testSubmitRejectsNonDraftPurchaseRequest(): void
    {
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $requests->method('find')->with(7)->willReturn([
            'id'     => 7,
            'status' => 'submitted',
        ]);

        $service = new PurchaseRequestService($requests, $approvalWorkflow);

        $this->expectException(DomainException::class);
        $service->submit(7);
    }

    public function testCreateRejectsDecimalRequestedQuantity(): void
    {
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $service = new PurchaseRequestService($requests, $approvalWorkflow);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('whole number');

        $service->create([
            'requested_by' => 10,
            'request_date' => '2026-02-20',
            'items'        => [
                [
                    'item_name'     => 'Paracetamol',
                    'requested_qty' => '2.5',
                    'unit'          => 'box',
                ],
            ],
        ]);
    }

    public function testListFormProductsUsesCatalogService(): void
    {
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);
        $products         = $this->createMock(ProductService::class);

        $products->expects($this->once())
            ->method('listActive')
            ->willReturn([
                ['id' => 1, 'product_name' => 'Bandage', 'unit' => 'pack'],
            ]);

        $service = new PurchaseRequestService($requests, $approvalWorkflow, $products);

        $result = $service->listFormProducts();

        $this->assertCount(1, $result);
        $this->assertSame('Bandage', $result[0]['product_name']);
    }

    public function testListFormProductsIncludesInactiveSelectedProductsForEditing(): void
    {
        $requests         = $this->createMock(PurchaseRequestRepositoryInterface::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);
        $products         = $this->createMock(ProductService::class);

        $products->expects($this->once())
            ->method('listActive')
            ->willReturn([
                ['id' => 1, 'product_name' => 'Bandage', 'unit' => 'pack', 'is_active' => 1],
            ]);

        $products->expects($this->once())
            ->method('listAll')
            ->willReturn([
                ['id' => 1, 'product_name' => 'Bandage', 'unit' => 'pack', 'is_active' => 1],
                ['id' => 9, 'product_name' => 'Retired Item', 'unit' => 'box', 'is_active' => 0],
            ]);

        $service = new PurchaseRequestService($requests, $approvalWorkflow, $products);

        $result = $service->listFormProducts([9]);

        $this->assertCount(2, $result);
        $this->assertSame([1, 9], array_column($result, 'id'));
    }
}
