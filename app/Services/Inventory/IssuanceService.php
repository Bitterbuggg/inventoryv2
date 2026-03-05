<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemAllocationRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Services\Shared\AuditService;

class IssuanceService
{
    public function __construct(
        private readonly IssuanceRepositoryInterface $issuances,
        private readonly IssuanceItemRepositoryInterface $issuanceItems,
        private readonly IssuanceItemAllocationRepositoryInterface $issuanceItemAllocations,
        private readonly ApprovalRepositoryInterface $approvals,
        private readonly AuditService $audit,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $status = null): array
    {
        $filters = [];

        if ($status !== null && $status !== '') {
            $filters['status'] = $status;
        }

        return $this->issuances->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithItems(int $issuanceId): ?array
    {
        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            return null;
        }

        $issuance['items'] = $this->issuanceItems->listByIssuance($issuanceId);
        $issuance['allocations'] = $this->sortAllocationsByFefo(
            $this->issuanceItemAllocations->listByIssuance($issuanceId)
        );

        return $issuance;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createDraft(array $data): int
    {
        $requestorId = (int) ($data['requestor_id'] ?? 0);
        $issueDate   = trim((string) ($data['issue_date'] ?? ''));

        if ($requestorId <= 0) {
            throw new \InvalidArgumentException('Requestor is required.');
        }

        if ($issueDate === '') {
            throw new \InvalidArgumentException('Issue date is required.');
        }

        $items = $this->normalizeItems($data['items'] ?? []);

        if ($items === []) {
            throw new \DomainException('At least one issuance item is required.');
        }

        $issuanceId = $this->issuances->create([
            'issuance_number'  => $this->generateIssuanceNumber(),
            'requestor_id'     => $requestorId,
            'issue_date'       => $issueDate,
            'department'       => $this->nullableString($data['department'] ?? null),
            'purpose'          => $this->nullableString($data['purpose'] ?? null),
            'status'           => 'draft',
            'submitted_at'     => null,
            'approved_by'      => null,
            'approved_at'      => null,
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
            'released_by'      => null,
            'released_at'      => null,
            'remarks'          => $this->nullableString($data['remarks'] ?? null),
        ]);

        $this->issuanceItems->addItems($issuanceId, $items);

        $this->safeAudit(
            actorId: $requestorId,
            action: 'issuance.draft_created',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            newValues: [
                'status'     => 'draft',
                'item_count' => count($items),
                'issue_date' => $issueDate,
            ],
        );

        return $issuanceId;
    }

    public function submit(int $issuanceId, ?int $actorId = null): void
    {
        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            throw new \DomainException('Issuance record not found.');
        }

        if (($issuance['status'] ?? '') !== 'draft') {
            throw new \DomainException('Only draft issuances can be submitted.');
        }

        $items = $this->issuanceItems->listByIssuance($issuanceId);

        if ($items === []) {
            throw new \DomainException('Cannot submit issuance without items.');
        }

        $now = date('Y-m-d H:i:s');

        $this->issuances->update($issuanceId, [
            'status'       => 'submitted',
            'submitted_at' => $now,
        ]);

        $pendingApproval = $this->approvals->findPendingByReference('issuance', $issuanceId);

        if ($pendingApproval === null) {
            $this->approvals->create([
                'reference_type' => 'issuance',
                'reference_id'   => $issuanceId,
                'approval_level' => 1,
                'approver_id'    => null,
                'decision'       => 'pending',
                'decision_at'    => null,
                'comments'       => null,
            ]);
        }

        $this->safeAudit(
            actorId: $actorId,
            action: 'issuance.submitted',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            oldValues: ['status' => 'draft'],
            newValues: ['status' => 'submitted', 'submitted_at' => $now],
        );
    }

    public function cancel(int $issuanceId, int $actorId, ?string $reason = null): void
    {
        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            throw new \DomainException('Issuance record not found.');
        }

        $status = (string) ($issuance['status'] ?? '');

        if (! in_array($status, ['draft', 'submitted'], true)) {
            throw new \DomainException('Only draft or submitted issuances can be cancelled.');
        }

        $cancelReason = $this->nullableString($reason) ?? 'Cancelled by user.';
        $now          = date('Y-m-d H:i:s');

        $this->issuances->update($issuanceId, [
            'status'           => 'cancelled',
            'rejected_by'      => $actorId,
            'rejected_at'      => $now,
            'rejection_reason' => $cancelReason,
        ]);

        $pendingApproval = $this->approvals->findPendingByReference('issuance', $issuanceId);

        if ($pendingApproval !== null) {
            $this->approvals->update((int) $pendingApproval['id'], [
                'approver_id' => $actorId,
                'decision'    => 'rejected',
                'decision_at' => $now,
                'comments'    => $cancelReason,
            ]);
        }

        $this->safeAudit(
            actorId: $actorId,
            action: 'issuance.cancelled',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            oldValues: ['status' => $status],
            newValues: ['status' => 'cancelled', 'reason' => $cancelReason],
        );
    }

    private function generateIssuanceNumber(): string
    {
        do {
            $number = 'ISS-' . date('Ymd-His') . '-' . random_int(1000, 9999);
        } while ($this->issuances->findByNumber($number) !== null);

        return $number;
    }

    /**
     * @param mixed $items
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $normalized = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $itemName = trim((string) ($item['item_name'] ?? ''));

            if ($itemName === '') {
                continue;
            }

            $requestedQtyRaw = $item['requested_qty'] ?? 0;

            if (! is_numeric($requestedQtyRaw)) {
                throw new \DomainException('Requested quantity must be a valid number.');
            }

            $requestedQty = (float) $requestedQtyRaw;

            if ($requestedQty <= 0) {
                throw new \DomainException('Requested quantity must be greater than zero.');
            }

            if (abs($requestedQty - round($requestedQty)) > 0.00001) {
                throw new \DomainException('Requested quantity must be a whole number.');
            }

            $normalized[] = [
                'item_name'          => $itemName,
                'unit'               => trim((string) ($item['unit'] ?? 'unit')) ?: 'unit',
                'inventory_stock_id' => null,
                'requested_qty'      => (float) round($requestedQty),
                'issued_qty'         => 0,
                'unit_cost'          => 0,
                'line_total'         => 0,
                'remarks'            => $this->nullableString($item['remarks'] ?? null),
            ];
        }

        return $normalized;
    }

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    /**
     * @param array<int, array<string, mixed>> $allocations
     *
     * @return array<int, array<string, mixed>>
     */
    private function sortAllocationsByFefo(array $allocations): array
    {
        usort($allocations, static function (array $left, array $right): int {
            $leftExpiry = trim((string) ($left['expiry_date'] ?? ''));
            $rightExpiry = trim((string) ($right['expiry_date'] ?? ''));

            $leftHasExpiry = $leftExpiry !== '';
            $rightHasExpiry = $rightExpiry !== '';

            if ($leftHasExpiry && ! $rightHasExpiry) {
                return -1;
            }

            if (! $leftHasExpiry && $rightHasExpiry) {
                return 1;
            }

            if ($leftHasExpiry && $rightHasExpiry) {
                $expiryCompare = strcmp($leftExpiry, $rightExpiry);
                if ($expiryCompare !== 0) {
                    return $expiryCompare;
                }
            }

            $leftId = (int) ($left['id'] ?? 0);
            $rightId = (int) ($right['id'] ?? 0);

            return $leftId <=> $rightId;
        });

        return $allocations;
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
