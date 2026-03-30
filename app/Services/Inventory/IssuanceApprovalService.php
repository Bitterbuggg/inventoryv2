<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Services\Shared\AuditService;
use App\Services\Shared\ApprovalWorkflowService;

class IssuanceApprovalService
{
    public function __construct(
        private readonly IssuanceRepositoryInterface $issuances,
        private readonly ApprovalWorkflowService $approvalWorkflow,
        private readonly AuditService $audit,
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

        $resolvedApproval = $this->approvalWorkflow->resolvePendingApprovalByReference(
            'issuance',
            $issuanceId,
            $approverId,
            'approved',
            $comments,
        );

        $now = (string) ($resolvedApproval['decision_at'] ?? date('Y-m-d H:i:s'));

        $this->issuances->update($issuanceId, [
            'status'           => 'approved',
            'approved_by'      => $approverId,
            'approved_at'      => $now,
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);

        $this->safeAudit(
            actorId: $approverId,
            action: 'issuance.approved',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            oldValues: ['status' => 'submitted'],
            newValues: ['status' => 'approved', 'comments' => $resolvedApproval['comments'] ?? null],
        );
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

        $resolvedApproval = $this->approvalWorkflow->resolvePendingApprovalByReference(
            'issuance',
            $issuanceId,
            $approverId,
            'rejected',
            $reason,
        );

        $now = (string) ($resolvedApproval['decision_at'] ?? date('Y-m-d H:i:s'));

        $this->issuances->update($issuanceId, [
            'status'           => 'rejected',
            'rejected_by'      => $approverId,
            'rejected_at'      => $now,
            'rejection_reason' => $reason,
        ]);

        $this->safeAudit(
            actorId: $approverId,
            action: 'issuance.rejected',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            oldValues: ['status' => 'submitted'],
            newValues: ['status' => 'rejected', 'reason' => $reason],
        );
    }

    /**
     * @param array<string, mixed>|null $oldValues
     * @param array<string, mixed>|null $newValues
     */
    private function safeAudit(
        ?int $actorId,
        string $action,
        string $module,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        try {
            $this->audit->log($actorId, $action, $module, $referenceType, $referenceId, $oldValues, $newValues);
        } catch (\Throwable) {
            // Audit logging should not block the primary workflow.
        }
    }
}
