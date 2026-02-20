<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;

class IssuanceApprovalService
{
    public function __construct(
        private readonly IssuanceRepositoryInterface $issuances,
        private readonly ApprovalRepositoryInterface $approvals,
    ) {
    }

    public function approve(int $issuanceId, int $approverId, ?string $comments = null): void
    {
        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            throw new \DomainException('Issuance record not found.');
        }

        if (($issuance['status'] ?? '') !== 'submitted') {
            throw new \DomainException('Only submitted issuances can be approved.');
        }

        $pendingApproval = $this->approvals->findPendingByReference('issuance', $issuanceId);

        if ($pendingApproval === null) {
            throw new \DomainException('Pending approval record for issuance was not found.');
        }

        $now = date('Y-m-d H:i:s');

        $this->approvals->update((int) $pendingApproval['id'], [
            'approver_id' => $approverId,
            'decision'    => 'approved',
            'decision_at' => $now,
            'comments'    => $this->nullableString($comments),
        ]);

        $this->issuances->update($issuanceId, [
            'status'           => 'approved',
            'approved_by'      => $approverId,
            'approved_at'      => $now,
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);
    }

    public function reject(int $issuanceId, int $approverId, string $reason): void
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw new \DomainException('Rejection reason is required.');
        }

        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            throw new \DomainException('Issuance record not found.');
        }

        if (($issuance['status'] ?? '') !== 'submitted') {
            throw new \DomainException('Only submitted issuances can be rejected.');
        }

        $pendingApproval = $this->approvals->findPendingByReference('issuance', $issuanceId);

        if ($pendingApproval === null) {
            throw new \DomainException('Pending approval record for issuance was not found.');
        }

        $now = date('Y-m-d H:i:s');

        $this->approvals->update((int) $pendingApproval['id'], [
            'approver_id' => $approverId,
            'decision'    => 'rejected',
            'decision_at' => $now,
            'comments'    => $reason,
        ]);

        $this->issuances->update($issuanceId, [
            'status'           => 'rejected',
            'rejected_by'      => $approverId,
            'rejected_at'      => $now,
            'rejection_reason' => $reason,
        ]);
    }

    private function nullableString(?string $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
