<?php

namespace App\Services\Procurement;

use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use DomainException;

class ApprovalService
{
    public function __construct(
        private readonly ApprovalRepositoryInterface $approvals,
        private readonly PurchaseRequestRepositoryInterface $purchaseRequests,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPending(): array
    {
        return $this->approvals->listPending();
    }

    public function approve(int $approvalId, int $approverId, ?string $comments = null): void
    {
        $approval = $this->approvals->find($approvalId);

        if ($approval === null) {
            throw new DomainException('Approval record not found.');
        }

        if (($approval['decision'] ?? '') !== 'pending') {
            throw new DomainException('Approval has already been resolved.');
        }

        if (($approval['reference_type'] ?? '') !== 'purchase_request') {
            throw new DomainException('Unsupported approval reference type.');
        }

        $purchaseRequestId = (int) ($approval['reference_id'] ?? 0);
        $purchaseRequest   = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request for approval was not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'submitted') {
            throw new DomainException('Only submitted purchase requests can be approved.');
        }

        $now = date('Y-m-d H:i:s');

        $this->approvals->update($approvalId, [
            'approver_id' => $approverId,
            'decision'    => 'approved',
            'decision_at' => $now,
            'comments'    => $this->nullableText($comments),
        ]);

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

        $approval = $this->approvals->find($approvalId);

        if ($approval === null) {
            throw new DomainException('Approval record not found.');
        }

        if (($approval['decision'] ?? '') !== 'pending') {
            throw new DomainException('Approval has already been resolved.');
        }

        if (($approval['reference_type'] ?? '') !== 'purchase_request') {
            throw new DomainException('Unsupported approval reference type.');
        }

        $purchaseRequestId = (int) ($approval['reference_id'] ?? 0);
        $purchaseRequest   = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request for approval was not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'submitted') {
            throw new DomainException('Only submitted purchase requests can be rejected.');
        }

        $now = date('Y-m-d H:i:s');

        $this->approvals->update($approvalId, [
            'approver_id' => $approverId,
            'decision'    => 'rejected',
            'decision_at' => $now,
            'comments'    => $reason,
        ]);

        $this->purchaseRequests->update($purchaseRequestId, [
            'status'           => 'rejected',
            'rejected_by'      => $approverId,
            'rejected_at'      => $now,
            'rejection_reason' => $reason,
        ]);
    }

    private function nullableText(?string $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
