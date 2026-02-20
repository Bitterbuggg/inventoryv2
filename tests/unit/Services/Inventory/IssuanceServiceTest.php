<?php

use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Services\Inventory\IssuanceService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class IssuanceServiceTest extends CIUnitTestCase
{
    public function testCreateDraftStoresIssuanceAndItems(): void
    {
        $issuances     = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems = $this->createMock(IssuanceItemRepositoryInterface::class);
        $approvals     = $this->createMock(ApprovalRepositoryInterface::class);

        $issuances->method('findByNumber')->willReturn(null);

        $issuances->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('issuance_number'))
            ->willReturn(55);

        $issuanceItems->expects($this->once())
            ->method('addItems')
            ->with(55, $this->callback(static fn (array $rows): bool => count($rows) === 1 && $rows[0]['item_name'] === 'Paracetamol 500mg'));

        $service = new IssuanceService($issuances, $issuanceItems, $approvals);

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
        $issuances     = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems = $this->createMock(IssuanceItemRepositoryInterface::class);
        $approvals     = $this->createMock(ApprovalRepositoryInterface::class);

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

        $approvals->expects($this->once())
            ->method('findPendingByReference')
            ->with('issuance', 9)
            ->willReturn(null);

        $approvals->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('reference_type'));

        $service = new IssuanceService($issuances, $issuanceItems, $approvals);
        $service->submit(9);

        $this->assertTrue(true);
    }

    public function testSubmitRejectsNonDraftIssuance(): void
    {
        $issuances     = $this->createMock(IssuanceRepositoryInterface::class);
        $issuanceItems = $this->createMock(IssuanceItemRepositoryInterface::class);
        $approvals     = $this->createMock(ApprovalRepositoryInterface::class);

        $issuances->method('find')->with(12)->willReturn([
            'id'     => 12,
            'status' => 'submitted',
        ]);

        $service = new IssuanceService($issuances, $issuanceItems, $approvals);

        $this->expectException(DomainException::class);
        $service->submit(12);
    }
}
