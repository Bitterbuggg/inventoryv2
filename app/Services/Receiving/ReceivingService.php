<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Services\Shared\AuditService;
use CodeIgniter\Database\BaseConnection;

class ReceivingService
{
    public function __construct(
        private readonly ReceivingRepositoryInterface $receivings,
        private readonly ReceivingItemRepositoryInterface $receivingItems,
        private readonly PoRequestRepositoryInterface $poRequests,
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
        private readonly ReceivingValidationService $validation,
        private readonly InventoryPostingService $inventoryPosting,
        private readonly BaseConnection $db,
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

        return $this->receivings->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithItems(int $receivingId): ?array
    {
        $receiving = $this->receivings->find($receivingId);

        if ($receiving === null) {
            return null;
        }

        $receiving['items'] = $this->receivingItems->listByReceiving($receivingId);

        return $receiving;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listConvertiblePoRequests(): array
    {
        $approvedPoRequests = $this->poRequests->list(['status' => 'approved']);
        $convertible = [];

        foreach ($approvedPoRequests as $poRequest) {
            $poRequestId = (int) ($poRequest['id'] ?? 0);

            if ($poRequestId <= 0) {
                continue;
            }

            $existingReceiving = $this->receivings->findByPoRequest($poRequestId);
            if ($existingReceiving !== null && ($existingReceiving['status'] ?? '') !== 'voided') {
                continue;
            }

            $convertible[] = $poRequest;
        }

        return $convertible;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildConversionData(int $poRequestId): array
    {
        $poRequest = $this->poRequests->find($poRequestId);

        if ($poRequest === null) {
            throw new \DomainException('PO request not found.');
        }

        if (($poRequest['status'] ?? '') !== 'approved') {
            throw new \DomainException('Only approved PO requests can be converted to receiving.');
        }

        $existingReceiving = $this->receivings->findByPoRequest($poRequestId);
        if ($existingReceiving !== null && ($existingReceiving['status'] ?? '') !== 'voided') {
            throw new \DomainException('A receiving record already exists for this PO request.');
        }

        $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);
        $purchaseOrder   = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new \DomainException('Purchase order for PO request was not found.');
        }

        $purchaseOrderItems = $this->purchaseOrders->listItems($purchaseOrderId);

        if ($purchaseOrderItems === []) {
            throw new \DomainException('Purchase order has no items to receive.');
        }

        $items = [];

        foreach ($purchaseOrderItems as $purchaseOrderItem) {
            $orderedQty   = (float) ($purchaseOrderItem['ordered_qty'] ?? 0);
            $receivedQty  = (float) ($purchaseOrderItem['received_qty'] ?? 0);
            $remainingQty = $orderedQty - $receivedQty;

            if ($remainingQty <= 0) {
                continue;
            }

            $unitCost = (float) ($purchaseOrderItem['unit_cost'] ?? 0);

            $items[] = [
                'purchase_order_item_id' => (int) ($purchaseOrderItem['id'] ?? 0),
                'item_name'              => (string) ($purchaseOrderItem['item_name'] ?? ''),
                'unit'                   => (string) ($purchaseOrderItem['unit'] ?? 'unit'),
                'received_qty'           => $remainingQty,
                'accepted_qty'           => $remainingQty,
                'rejected_qty'           => 0,
                'batch_no'               => null,
                'lot_no'                 => null,
                'expiry_date'            => null,
                'unit_cost'              => $unitCost,
                'line_total'             => round($remainingQty * $unitCost, 2),
                'remarks'                => null,
            ];
        }

        if ($items === []) {
            throw new \DomainException('No remaining quantities are available for receiving.');
        }

        return [
            'po_request'     => $poRequest,
            'purchase_order' => $purchaseOrder,
            'items'          => $items,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createDraft(array $data): int
    {
        $poRequestId = (int) ($data['po_request_id'] ?? 0);

        if ($poRequestId <= 0) {
            throw new \InvalidArgumentException('PO request is required.');
        }

        $poRequest = $this->poRequests->find($poRequestId);

        if ($poRequest === null) {
            throw new \DomainException('PO request not found.');
        }

        if (($poRequest['status'] ?? '') !== 'approved') {
            throw new \DomainException('Only approved PO requests can create receiving draft.');
        }

        $existingReceiving = $this->receivings->findByPoRequest($poRequestId);
        if ($existingReceiving !== null && ($existingReceiving['status'] ?? '') !== 'voided') {
            throw new \DomainException('A receiving record already exists for this PO request.');
        }

        $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);
        $purchaseOrder   = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new \DomainException('Purchase order for PO request was not found.');
        }

        $items = $this->normalizeItems($data['items'] ?? []);

        if ($items === []) {
            throw new \DomainException('At least one receiving item is required.');
        }

        $purchaseOrderItems      = $this->purchaseOrders->listItems($purchaseOrderId);
        $purchaseOrderItemsById  = $this->mapPurchaseOrderItems($purchaseOrderItems);

        $this->validation->assertValid($items, $purchaseOrderItemsById);

        $receivingId = $this->receivings->create([
            'receiving_number'   => $this->generateReceivingNumber(),
            'po_request_id'      => $poRequestId,
            'purchase_order_id'  => $purchaseOrderId,
            'supplier_name'      => $purchaseOrder['supplier_name'] ?? null,
            'received_date'      => (string) ($data['received_date'] ?? date('Y-m-d')),
            'delivery_reference' => $this->nullableString($data['delivery_reference'] ?? null),
            'received_by'        => (int) ($data['received_by'] ?? 0),
            'verified_by'        => null,
            'status'             => 'draft',
            'remarks'            => $this->nullableString($data['remarks'] ?? null),
            'posted_at'          => null,
            'voided_at'          => null,
            'voided_by'          => null,
            'void_reason'        => null,
        ]);

        $this->receivingItems->addItems($receivingId, $items);

        return $receivingId;
    }

    /**
     * @return array<int, string>
     */
    public function validateDraft(int $receivingId): array
    {
        $receiving = $this->receivings->find($receivingId);

        if ($receiving === null) {
            return ['Receiving record not found.'];
        }

        $items = $this->receivingItems->listByReceiving($receivingId);

        if ($items === []) {
            return ['Receiving has no items.'];
        }

        $purchaseOrderItemsById = $this->mapPurchaseOrderItems(
            $this->purchaseOrders->listItems((int) ($receiving['purchase_order_id'] ?? 0)),
        );

        return $this->validation->validateItems($items, $purchaseOrderItemsById);
    }

    public function post(int $receivingId, int $actorId): void
    {
        $receiving = $this->receivings->find($receivingId);

        if ($receiving === null) {
            throw new \DomainException('Receiving record not found.');
        }

        if (($receiving['status'] ?? '') !== 'draft') {
            throw new \DomainException('Only draft receiving records can be posted.');
        }

        $poRequest = $this->poRequests->find((int) ($receiving['po_request_id'] ?? 0));
        if ($poRequest === null) {
            throw new \DomainException('PO request for receiving was not found.');
        }

        if (($poRequest['status'] ?? '') !== 'approved') {
            throw new \DomainException('PO request must be approved before posting receiving.');
        }

        $items = $this->receivingItems->listByReceiving($receivingId);
        if ($items === []) {
            throw new \DomainException('Receiving has no items to post.');
        }

        $purchaseOrderItemsById = $this->mapPurchaseOrderItems(
            $this->purchaseOrders->listItems((int) ($receiving['purchase_order_id'] ?? 0)),
        );
        $this->validation->assertValid($items, $purchaseOrderItemsById);

        $this->db->transBegin();

        try {
            foreach ($items as $item) {
                $lineTotal = round((float) ($item['accepted_qty'] ?? 0) * (float) ($item['unit_cost'] ?? 0), 2);
                $this->receivingItems->update((int) ($item['id'] ?? 0), ['line_total' => $lineTotal]);
            }

            $this->inventoryPosting->postReceivingItems($receivingId, $items, $actorId);

            $now = date('Y-m-d H:i:s');

            $this->receivings->update($receivingId, [
                'status'     => 'posted',
                'verified_by' => $actorId,
                'posted_at'  => $now,
            ]);

            $this->poRequests->update((int) ($receiving['po_request_id'] ?? 0), [
                'status' => 'converted_to_receiving',
            ]);

            $this->updatePurchaseOrderStatus((int) ($receiving['purchase_order_id'] ?? 0));
        } catch (\Throwable $exception) {
            $this->db->transRollback();
            throw $exception;
        }

        if (! $this->db->transStatus()) {
            $this->db->transRollback();
            throw new \RuntimeException('Receiving posting transaction failed.');
        }

        $this->db->transCommit();

        $this->safeAudit(
            actorId: $actorId,
            action: 'receiving.posted',
            module: 'receiving',
            referenceType: 'receiving',
            referenceId: $receivingId,
            oldValues: ['status' => 'draft'],
            newValues: ['status' => 'posted', 'posted_at' => $now],
        );
    }

    public function void(int $receivingId, int $actorId, string $reason): void
    {
        $receiving = $this->receivings->find($receivingId);

        if ($receiving === null) {
            throw new \DomainException('Receiving record not found.');
        }

        if (($receiving['status'] ?? '') !== 'draft') {
            throw new \DomainException('Only draft receiving records can be voided.');
        }

        $reason = trim($reason);
        if ($reason === '') {
            throw new \DomainException('Void reason is required.');
        }

        $now = date('Y-m-d H:i:s');

        $this->receivings->update($receivingId, [
            'status'      => 'voided',
            'voided_at'   => $now,
            'voided_by'   => $actorId,
            'void_reason' => $reason,
        ]);

        $this->safeAudit(
            actorId: $actorId,
            action: 'receiving.voided',
            module: 'receiving',
            referenceType: 'receiving',
            referenceId: $receivingId,
            oldValues: ['status' => 'draft'],
            newValues: ['status' => 'voided', 'void_reason' => $reason, 'voided_at' => $now],
        );
    }

    private function generateReceivingNumber(): string
    {
        do {
            $number = 'RCV-' . date('Ymd-His') . '-' . random_int(1000, 9999);
        } while ($this->receivings->findByNumber($number) !== null);

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

            $purchaseOrderItemId = (int) ($item['purchase_order_item_id'] ?? 0);
            $itemName            = trim((string) ($item['item_name'] ?? ''));

            if ($purchaseOrderItemId <= 0 || $itemName === '') {
                continue;
            }

            $receivedQty = (float) ($item['received_qty'] ?? 0);
            $acceptedQty = (float) ($item['accepted_qty'] ?? 0);
            $unitCost    = (float) ($item['unit_cost'] ?? 0);

            $normalized[] = [
                'purchase_order_item_id' => $purchaseOrderItemId,
                'item_name'              => $itemName,
                'unit'                   => trim((string) ($item['unit'] ?? 'unit')) ?: 'unit',
                'received_qty'           => $receivedQty,
                'accepted_qty'           => $acceptedQty,
                'rejected_qty'           => (float) ($item['rejected_qty'] ?? 0),
                'batch_no'               => $this->nullableString($item['batch_no'] ?? null),
                'lot_no'                 => $this->nullableString($item['lot_no'] ?? null),
                'expiry_date'            => $this->nullableString($item['expiry_date'] ?? null),
                'unit_cost'              => $unitCost,
                'line_total'             => round($acceptedQty * $unitCost, 2),
                'remarks'                => $this->nullableString($item['remarks'] ?? null),
            ];
        }

        return $normalized;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     *
     * @return array<int, array<string, mixed>>
     */
    private function mapPurchaseOrderItems(array $items): array
    {
        $map = [];

        foreach ($items as $item) {
            $itemId = (int) ($item['id'] ?? 0);

            if ($itemId > 0) {
                $map[$itemId] = $item;
            }
        }

        return $map;
    }

    private function updatePurchaseOrderStatus(int $purchaseOrderId): void
    {
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);
        if ($purchaseOrder === null) {
            return;
        }

        $items = $this->purchaseOrders->listItems($purchaseOrderId);
        if ($items === []) {
            return;
        }

        $allReceived = true;
        $anyReceived = false;

        foreach ($items as $item) {
            $orderedQty  = (float) ($item['ordered_qty'] ?? 0);
            $receivedQty = (float) ($item['received_qty'] ?? 0);

            if ($receivedQty > 0) {
                $anyReceived = true;
            }

            if ($receivedQty + 0.0005 < $orderedQty) {
                $allReceived = false;
            }
        }

        $status = null;

        if ($allReceived) {
            $status = 'fully_received';
        } elseif ($anyReceived) {
            $status = 'partially_received';
        }

        if ($status !== null) {
            $this->purchaseOrders->update($purchaseOrderId, ['status' => $status]);
        }
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

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}

