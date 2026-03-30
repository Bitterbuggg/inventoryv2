<?php

namespace App\Services\Shared;

use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use DomainException;

class ApprovalWorkflowService
{
    public function __construct(private readonly ApprovalRepositoryInterface $approvals)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPendingByReferenceType(string $referenceType): array
    {
        return array_values(array_filter(
            $this->approvals->listPending(),
            static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === $referenceType,
        ));
    }

    public function ensurePendingApproval(string $referenceType, int $referenceId, int $approvalLevel = 1): int
    {
        $pendingApproval = $this->approvals->findPendingByReference($referenceType, $referenceId);

        if ($pendingApproval !== null) {
            return (int) ($pendingApproval['id'] ?? 0);
        }

        return $this->approvals->create([
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
            'approval_level' => $approvalLevel,
            'approver_id'    => null,
            'decision'       => 'pending',
            'decision_at'    => null,
            'comments'       => null,
        ]);
    }

    /**
     * @return array{approval_id: int, reference_type: string, reference_id: int, decision: string, decision_at: string, comments: ?string}
     */
    public function resolvePendingApprovalById(
        int $approvalId,
        string $expectedReferenceType,
        int $approverId,
        string $decision,
        ?string $comments = null,
    ): array {
        $approval = $this->approvals->find($approvalId);

        if ($approval === null) {
            throw new DomainException('Approval record not found.');
        }

        return $this->resolveApproval($approval, $expectedReferenceType, $approverId, $decision, $comments);
    }

    /**
     * @return array{approval_id: int, reference_type: string, reference_id: int, decision: string, decision_at: string, comments: ?string}
     */
    public function resolvePendingApprovalByReference(
        string $referenceType,
        int $referenceId,
        int $approverId,
        string $decision,
        ?string $comments = null,
    ): array {
        $approval = $this->approvals->findPendingByReference($referenceType, $referenceId);

        if ($approval === null) {
            throw new DomainException($this->missingPendingApprovalMessage($referenceType));
        }

        return $this->resolveApproval($approval, $referenceType, $approverId, $decision, $comments);
    }

    public function rejectPendingApprovalIfExists(
        string $referenceType,
        int $referenceId,
        int $approverId,
        string $comments,
    ): void {
        $approval = $this->approvals->findPendingByReference($referenceType, $referenceId);

        if ($approval === null) {
            return;
        }

        $this->resolveApproval($approval, $referenceType, $approverId, 'rejected', $comments);
    }

    /**
     * @param array<string, mixed> $approval
     *
     * @return array{approval_id: int, reference_type: string, reference_id: int, decision: string, decision_at: string, comments: ?string}
     */
    private function resolveApproval(
        array $approval,
        string $expectedReferenceType,
        int $approverId,
        string $decision,
        ?string $comments,
    ): array {
        $decision = trim($decision);

        if (! in_array($decision, ['approved', 'rejected'], true)) {
            throw new DomainException('Unsupported approval decision.');
        }

        if (($approval['decision'] ?? '') !== 'pending') {
            throw new DomainException('Approval has already been resolved.');
        }

        if (($approval['reference_type'] ?? '') !== $expectedReferenceType) {
            throw new DomainException('Unsupported approval reference type.');
        }

        $normalizedComments = $this->nullableText($comments);
        $now = date('Y-m-d H:i:s');
        $approvalId = (int) ($approval['id'] ?? 0);

        $this->approvals->update($approvalId, [
            'approver_id' => $approverId,
            'decision'    => $decision,
            'decision_at' => $now,
            'comments'    => $normalizedComments,
        ]);

        return [
            'approval_id'    => $approvalId,
            'reference_type' => (string) ($approval['reference_type'] ?? ''),
            'reference_id'   => (int) ($approval['reference_id'] ?? 0),
            'decision'       => $decision,
            'decision_at'    => $now,
            'comments'       => $normalizedComments,
        ];
    }

    private function nullableText(?string $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function missingPendingApprovalMessage(string $referenceType): string
    {
        return match ($referenceType) {
            'issuance' => 'Pending approval record for issuance was not found.',
            'purchase_request' => 'Pending approval record for purchase request was not found.',
            default => 'Pending approval record not found.',
        };
    }
}
