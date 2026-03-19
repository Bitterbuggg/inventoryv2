<?php

namespace App\Services\Procurement;

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use DomainException;

class PoRequestService
{
    public function __construct(
        private readonly PoRequestRepositoryInterface $poRequests,
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
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

        return $this->poRequests->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $poRequestId): ?array
    {
        return $this->poRequests->find($poRequestId);
    }

    public function createFromPurchaseOrder(int $purchaseOrderId, int $requestedBy): int
    {
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new DomainException('Purchase order not found.');
        }

        if (($purchaseOrder['status'] ?? '') !== 'issued') {
            throw new DomainException('PO request can only be created from issued purchase orders.');
        }

        $existing = $this->poRequests->findByPurchaseOrder($purchaseOrderId);

        if ($existing !== null && in_array((string) ($existing['status'] ?? ''), ['pending', 'approved'], true)) {
            throw new DomainException('A PO request is already open for this purchase order.');
        }

        return $this->poRequests->create([
            'po_request_number' => $this->generatePoRequestNumber(),
            'purchase_order_id' => $purchaseOrderId,
            'requested_by'      => $requestedBy,
            'request_date'      => date('Y-m-d'),
            'status'            => 'pending',
            'approved_by'       => null,
            'approved_at'       => null,
            'rejected_by'       => null,
            'rejected_at'       => null,
            'rejection_reason'  => null,
        ]);
    }

    public function approve(int $poRequestId, int $approverId): void
    {
        $poRequest = $this->poRequests->find($poRequestId);

        if ($poRequest === null) {
            throw new DomainException('PO request not found.');
        }

        if (($poRequest['status'] ?? '') !== 'pending') {
            throw new DomainException('Only pending PO requests can be approved.');
        }

        $this->poRequests->update($poRequestId, [
            'status'           => 'approved',
            'approved_by'      => $approverId,
            'approved_at'      => date('Y-m-d H:i:s'),
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);
    }

    public function reject(int $poRequestId, int $approverId, string $reason): void
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw new DomainException('Rejection reason is required.');
        }

        $poRequest = $this->poRequests->find($poRequestId);

        if ($poRequest === null) {
            throw new DomainException('PO request not found.');
        }

        if (($poRequest['status'] ?? '') !== 'pending') {
            throw new DomainException('Only pending PO requests can be rejected.');
        }

        $this->poRequests->update($poRequestId, [
            'status'           => 'rejected',
            'rejected_by'      => $approverId,
            'rejected_at'      => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
        ]);
    }

    private function generatePoRequestNumber(): string
    {
        do {
            $number = 'POR-' . date('Ymd-His') . '-' . substr((string)round(microtime(true) * 1000), -4);
        } while ($this->poRequests->findByNumber($number) !== null);

        return $number;
    }
}
