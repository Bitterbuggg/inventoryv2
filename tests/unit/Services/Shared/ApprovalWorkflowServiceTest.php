<?php

use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Services\Shared\ApprovalWorkflowService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ApprovalWorkflowServiceTest extends CIUnitTestCase
{
    public function testListPendingByReferenceTypeFiltersRows(): void
    {
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $approvals->expects($this->once())
            ->method('listPending')
            ->willReturn([
                ['id' => 1, 'reference_type' => 'purchase_request', 'reference_id' => 12],
                ['id' => 2, 'reference_type' => 'issuance', 'reference_id' => 15],
            ]);

        $service = new ApprovalWorkflowService($approvals);

        $result = $service->listPendingByReferenceType('purchase_request');

        $this->assertCount(1, $result);
        $this->assertSame(1, $result[0]['id']);
    }

    public function testEnsurePendingApprovalReturnsExistingPendingId(): void
    {
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $approvals->expects($this->once())
            ->method('findPendingByReference')
            ->with('issuance', 21)
            ->willReturn(['id' => 9, 'reference_type' => 'issuance', 'reference_id' => 21, 'decision' => 'pending']);
        $approvals->expects($this->never())->method('create');

        $service = new ApprovalWorkflowService($approvals);

        $result = $service->ensurePendingApproval('issuance', 21);

        $this->assertSame(9, $result);
    }

    public function testEnsurePendingApprovalCreatesWhenMissing(): void
    {
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $approvals->expects($this->once())
            ->method('findPendingByReference')
            ->with('purchase_request', 14)
            ->willReturn(null);
        $approvals->expects($this->once())
            ->method('create')
            ->with($this->callback(static function (array $data): bool {
                return ($data['reference_type'] ?? null) === 'purchase_request'
                    && (int) ($data['reference_id'] ?? 0) === 14
                    && (int) ($data['approval_level'] ?? 0) === 1
                    && ($data['decision'] ?? null) === 'pending'
                    && array_key_exists('approver_id', $data) && $data['approver_id'] === null
                    && array_key_exists('decision_at', $data) && $data['decision_at'] === null
                    && array_key_exists('comments', $data) && $data['comments'] === null;
            }))
            ->willReturn(33);

        $service = new ApprovalWorkflowService($approvals);

        $result = $service->ensurePendingApproval('purchase_request', 14);

        $this->assertSame(33, $result);
    }

    public function testResolvePendingApprovalByIdUpdatesApprovalRow(): void
    {
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $approvals->expects($this->once())
            ->method('find')
            ->with(5)
            ->willReturn([
                'id' => 5,
                'reference_type' => 'purchase_request',
                'reference_id' => 18,
                'decision' => 'pending',
            ]);
        $approvals->expects($this->once())
            ->method('update')
            ->with(5, $this->callback(static function (array $data): bool {
                return (int) ($data['approver_id'] ?? 0) === 7
                    && ($data['decision'] ?? null) === 'approved'
                    && is_string($data['decision_at'] ?? null)
                    && ($data['comments'] ?? null) === 'Looks good';
            }))
            ->willReturn(true);

        $service = new ApprovalWorkflowService($approvals);

        $result = $service->resolvePendingApprovalById(5, 'purchase_request', 7, 'approved', '  Looks good  ');

        $this->assertSame(5, $result['approval_id']);
        $this->assertSame(18, $result['reference_id']);
        $this->assertSame('approved', $result['decision']);
        $this->assertSame('Looks good', $result['comments']);
        $this->assertIsString($result['decision_at']);
    }

    public function testRejectPendingApprovalIfExistsSkipsWhenMissing(): void
    {
        $approvals = $this->createMock(ApprovalRepositoryInterface::class);

        $approvals->expects($this->once())
            ->method('findPendingByReference')
            ->with('issuance', 44)
            ->willReturn(null);
        $approvals->expects($this->never())->method('update');

        $service = new ApprovalWorkflowService($approvals);

        $service->rejectPendingApprovalIfExists('issuance', 44, 6, 'Cancelled by user.');

        $this->assertTrue(true);
    }
}
