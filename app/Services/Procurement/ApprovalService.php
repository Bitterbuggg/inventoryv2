<?php

namespace App\Services\Procurement;

use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Services\Shared\ApprovalWorkflowService;
use DomainException;

class ApprovalService
{
    public function __construct(
        private readonly ApprovalWorkflowService $approvalWorkflow,
        private readonly PurchaseRequestRepositoryInterface $purchaseRequests,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPending(): array
    {
        return $this->approvalWorkflow->listPendingByReferenceType('purchase_request');
    }

    public function approve(int $approvalId, int $approverId, ?string $comments = null): void
    {
        $resolvedApproval = $this->approvalWorkflow->resolvePendingApprovalById(
            $approvalId,
            'purchase_request',
            $approverId,
            'approved',
            $comments,
        );

        $purchaseRequestId = (int) ($resolvedApproval['reference_id'] ?? 0);
        $purchaseRequest   = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request for approval was not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'submitted') {
            throw new DomainException('Only submitted purchase requests can be approved.');
        }

        $now = (string) ($resolvedApproval['decision_at'] ?? date('Y-m-d H:i:s'));

        $this->purchaseRequests->update($purchaseRequestId, [
            'status'           => 'approved',
            'approved_by'      => $approverId,
            'approved_at'      => $now,
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);
    }

    public function reject(int $approvalId, int $approverId, string $reason): void
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw new DomainException('Rejection reason is required.');
        }

        $resolvedApproval = $this->approvalWorkflow->resolvePendingApprovalById(
            $approvalId,
            'purchase_request',
            $approverId,
            'rejected',
            $reason,
        );

        $purchaseRequestId = (int) ($resolvedApproval['reference_id'] ?? 0);
        $purchaseRequest   = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request for approval was not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'submitted') {
            throw new DomainException('Only submitted purchase requests can be rejected.');
        }

        $now = (string) ($resolvedApproval['decision_at'] ?? date('Y-m-d H:i:s'));

        $this->purchaseRequests->update($purchaseRequestId, [
            'status'           => 'rejected',
            'rejected_by'      => $approverId,
            'rejected_at'      => $now,
            'rejection_reason' => $reason,
        ]);
    }
}
