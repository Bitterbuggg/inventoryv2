<?php

use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemAllocationRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Services\Catalog\ProductService;
use App\Services\Inventory\IssuanceService;
use App\Services\Shared\ApprovalWorkflowService;
use App\Services\Shared\AuditService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class IssuanceServiceTest extends CIUnitTestCase
{
    public function testCreateDraftStoresIssuanceAndItems(): void
    {
        $issuances        = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems    = $this->createMock(IssuanceItemRepositoryInterface::class);
        $allocations      = $this->createMock(IssuanceItemAllocationRepositoryInterface::class);
        $audit            = $this->createMock(AuditService::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $issuances->method('findByNumber')->willReturn(null);

        $issuances->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('issuance_number'))
            ->willReturn(55);

        $issuanceItems->expects($this->once())
            ->method('addItems')
            ->with(55, $this->callback(static fn (array $rows): bool => count($rows) === 1 && $rows[0]['item_name'] === 'Paracetamol 500mg'));

        $service = new IssuanceService($issuances, $issuanceItems, $allocations, $audit, $approvalWorkflow);

        $id = $service->createDraft([
            'requestor_id' => 10,
            'issue_date'   => '2026-02-20',
            'department'   => 'Pharmacy',
            'items'        => [[
                'item_name'     => 'Paracetamol 500mg',
                'unit'          => 'box',
                'requested_qty' => 5,
            ]],
        ]);

        $this->assertSame(55, $id);
    }

    public function testSubmitCreatesPendingApprovalWhenMissing(): void
    {
        $issuances        = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems    = $this->createMock(IssuanceItemRepositoryInterface::class);
        $allocations      = $this->createMock(IssuanceItemAllocationRepositoryInterface::class);
        $audit            = $this->createMock(AuditService::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $issuances->method('find')->with(9)->willReturn([
            'id'     => 9,
            'status' => 'draft',
        ]);

        $issuanceItems->method('listByIssuance')->with(9)->willReturn([
            ['id' => 1001, 'item_name' => 'Bandage', 'requested_qty' => 2],
        ]);

        $issuances->expects($this->once())
            ->method('update')
            ->with(9, $this->arrayHasKey('status'));

        $approvalWorkflow->expects($this->once())
            ->method('ensurePendingApproval')
            ->with('issuance', 9)
            ->willReturn(88);

        $service = new IssuanceService($issuances, $issuanceItems, $allocations, $audit, $approvalWorkflow);
        $service->submit(9, 1);

        $this->assertTrue(true);
    }

    public function testSubmitRejectsNonDraftIssuance(): void
    {
        $issuances        = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems    = $this->createMock(IssuanceItemRepositoryInterface::class);
        $allocations      = $this->createMock(IssuanceItemAllocationRepositoryInterface::class);
        $audit            = $this->createMock(AuditService::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $issuances->method('find')->with(12)->willReturn([
            'id'     => 12,
            'status' => 'submitted',
        ]);

        $service = new IssuanceService($issuances, $issuanceItems, $allocations, $audit, $approvalWorkflow);

        $this->expectException(DomainException::class);
        $service->submit(12, 1);
    }

    public function testCreateDraftRejectsDecimalRequestedQty(): void
    {
        $issuances        = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems    = $this->createMock(IssuanceItemRepositoryInterface::class);
        $allocations      = $this->createMock(IssuanceItemAllocationRepositoryInterface::class);
        $audit            = $this->createMock(AuditService::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);

        $service = new IssuanceService($issuances, $issuanceItems, $allocations, $audit, $approvalWorkflow);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('whole number');

        $service->createDraft([
            'requestor_id' => 10,
            'issue_date'   => '2026-02-20',
            'items'        => [[
                'item_name'     => 'Paracetamol 500mg',
                'unit'          => 'box',
                'requested_qty' => '1.5',
            ]],
        ]);
    }

    public function testListFormProductsUsesCatalogService(): void
    {
        $issuances        = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems    = $this->createMock(IssuanceItemRepositoryInterface::class);
        $allocations      = $this->createMock(IssuanceItemAllocationRepositoryInterface::class);
        $audit            = $this->createMock(AuditService::class);
        $approvalWorkflow = $this->createMock(ApprovalWorkflowService::class);
        $products         = $this->createMock(ProductService::class);

        $products->expects($this->once())
            ->method('listAvailableForIssuance')
            ->willReturn([
                ['id' => 9, 'product_name' => 'Paracetamol 500mg', 'available_qty' => 12],
            ]);

        $service = new IssuanceService($issuances, $issuanceItems, $allocations, $audit, $approvalWorkflow, $products);

        $result = $service->listFormProducts();

        $this->assertCount(1, $result);
        $this->assertSame('Paracetamol 500mg', $result[0]['product_name']);
    }
}
