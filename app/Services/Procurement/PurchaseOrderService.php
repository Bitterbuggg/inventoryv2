<?php

namespace App\Services\Procurement;

use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use DomainException;

class PurchaseOrderService
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
        private readonly PurchaseRequestRepositoryInterface $purchaseRequests,
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

        return $this->purchaseOrders->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithItems(int $purchaseOrderId): ?array
    {
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            return null;
        }

        $purchaseOrder['items'] = $this->purchaseOrders->listItems($purchaseOrderId);

        return $purchaseOrder;
    }

    public function createFromPurchaseRequest(int $purchaseRequestId, ?string $supplierName = null): int
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'approved') {
            throw new DomainException('Only approved purchase requests can be converted to purchase orders.');
        }

        if ($this->purchaseOrders->findByPurchaseRequest($purchaseRequestId) !== null) {
            throw new DomainException('Purchase order already exists for this purchase request.');
        }

        $requestItems = $this->purchaseRequests->listItems($purchaseRequestId);

        if ($requestItems === []) {
            throw new DomainException('Cannot create purchase order without request items.');
        }

        $purchaseOrderId = $this->purchaseOrders->create([
            'po_number'           => $this->generatePoNumber(),
            'purchase_request_id' => $purchaseRequestId,
            'supplier_name'       => $this->nullableText($supplierName),
            'order_date'          => date('Y-m-d'),
            'status'              => 'draft',
            'subtotal_amount'     => 0,
            'total_amount'        => 0,
            'issued_by'           => null,
            'issued_at'           => null,
        ]);

        $subtotal = 0.0;
        $poItems  = [];

        foreach ($requestItems as $requestItem) {
            $qty = (float) ($requestItem['approved_qty'] ?? $requestItem['requested_qty'] ?? 0);
            if ($qty <= 0) {
                $qty = (float) ($requestItem['requested_qty'] ?? 0);
            }

            $unitCost  = (float) ($requestItem['estimated_unit_cost'] ?? 0);
            $lineTotal = round($qty * $unitCost, 2);
            $subtotal += $lineTotal;

            $poItems[] = [
                'purchase_request_item_id' => $requestItem['id'] ?? null,
                'item_name'                => $requestItem['item_name'] ?? '',
                'unit'                     => $requestItem['unit'] ?? 'unit',
                'ordered_qty'              => $qty,
                'received_qty'             => 0,
                'unit_cost'                => $unitCost,
                'line_total'               => $lineTotal,
            ];
        }

        $this->purchaseOrders->addItems($purchaseOrderId, $poItems);

        $this->purchaseOrders->update($purchaseOrderId, [
            'subtotal_amount' => round($subtotal, 2),
            'total_amount'    => round($subtotal, 2),
        ]);

        $this->purchaseRequests->update($purchaseRequestId, [
            'status' => 'converted_to_po',
        ]);

        return $purchaseOrderId;
    }

    public function issue(int $purchaseOrderId, int $issuedBy): void
    {
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new DomainException('Purchase order not found.');
        }

        if (($purchaseOrder['status'] ?? '') !== 'draft') {
            throw new DomainException('Only draft purchase orders can be issued.');
        }

        $this->purchaseOrders->update($purchaseOrderId, [
            'status'    => 'issued',
            'issued_by' => $issuedBy,
            'issued_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function generatePoNumber(): string
    {
        do {
            $number = 'PO-' . date('Ymd-His') . '-' . substr((string)round(microtime(true) * 1000), -4);
        } while ($this->purchaseOrders->findByNumber($number) !== null);

        return $number;
    }

    private function nullableText(?string $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
