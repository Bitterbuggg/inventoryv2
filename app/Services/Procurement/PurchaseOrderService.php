<?php

namespace App\Services\Procurement;

use App\Services\Catalog\SupplierService;
use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use DomainException;
use RuntimeException;

class PurchaseOrderService
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
        private readonly PurchaseRequestRepositoryInterface $purchaseRequests,
        private readonly ?PoRequestRepositoryInterface $poRequests = null,
        private readonly ?SupplierService $suppliers = null,
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
     * @return array<int, array<string, mixed>>
     */
    public function listForIndex(?string $status = null): array
    {
        $purchaseOrders = $this->list($status);

        if ($purchaseOrders === []) {
            return [];
        }

        if ($this->poRequests === null) {
            throw new RuntimeException('PO request repository is unavailable.');
        }

        $poRequestByOrder = [];

        foreach ($this->poRequests->list() as $poRequest) {
            $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);

            if ($purchaseOrderId <= 0) {
                continue;
            }

            $current = $poRequestByOrder[$purchaseOrderId] ?? null;

            if ($current === null || (int) ($poRequest['id'] ?? 0) > (int) ($current['id'] ?? 0)) {
                $poRequestByOrder[$purchaseOrderId] = $poRequest;
            }
        }

        return array_map(static function (array $order) use ($poRequestByOrder): array {
            $purchaseOrderId = (int) ($order['id'] ?? 0);
            $linkedPoRequest = $poRequestByOrder[$purchaseOrderId] ?? null;
            $linkedStatus = strtolower((string) ($linkedPoRequest['status'] ?? ''));

            $order['po_request_status'] = $linkedPoRequest['status'] ?? null;
            $order['has_open_po_request'] = in_array(
                $linkedStatus,
                ['pending', 'approved', 'converted_to_receiving', 'closed'],
                true,
            );

            return $order;
        }, $purchaseOrders);
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listActiveSuppliers(): array
    {
        if ($this->suppliers === null) {
            throw new RuntimeException('Supplier catalog service is unavailable.');
        }

        return $this->suppliers->listActive();
    }

    public function createFromPurchaseRequest(int $purchaseRequestId, int|string|null $supplier = null): int
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request not found.');
        }

        if ($this->purchaseOrders->findByPurchaseRequest($purchaseRequestId) !== null) {
            throw new DomainException('Purchase order already exists for this purchase request.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'approved') {
            throw new DomainException('Only approved purchase requests can be converted to purchase orders.');
        }

        $requestItems = $this->purchaseRequests->listItems($purchaseRequestId);

        if ($requestItems === []) {
            throw new DomainException('Cannot create purchase order without request items.');
        }

        $resolvedSupplier = $this->resolveSupplier($supplier);

        if ($resolvedSupplier['supplier_name'] === null) {
            throw new DomainException('Supplier is required.');
        }

        $purchaseOrderData = [
            'po_number'           => $this->generatePoNumber(),
            'purchase_request_id' => $purchaseRequestId,
            'supplier_name'       => $resolvedSupplier['supplier_name'],
            'order_date'          => date('Y-m-d'),
            'status'              => 'draft',
            'subtotal_amount'     => 0,
            'total_amount'        => 0,
            'issued_by'           => null,
            'issued_at'           => null,
        ];

        if ($resolvedSupplier['supplier_id'] !== null) {
            $purchaseOrderData['supplier_id'] = $resolvedSupplier['supplier_id'];
        }

        $purchaseOrderId = $this->purchaseOrders->create($purchaseOrderData);

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
                'product_id'               => $requestItem['product_id'] ?? null,
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

    /**
     * @return array{supplier_id: ?int, supplier_name: ?string}
     */
    private function resolveSupplier(int|string|null $supplier): array
    {
        if (is_int($supplier) || ctype_digit((string) $supplier)) {
            $supplierId = (int) $supplier;

            if ($supplierId <= 0) {
                return ['supplier_id' => null, 'supplier_name' => null];
            }

            if ($this->suppliers === null) {
                throw new DomainException('Supplier catalog service is unavailable.');
            }

            $supplierRecord = $this->suppliers->getOrFail($supplierId);

            return [
                'supplier_id'   => (int) ($supplierRecord['id'] ?? 0),
                'supplier_name' => $this->nullableText((string) ($supplierRecord['supplier_name'] ?? '')),
            ];
        }

        $supplierName = $this->nullableText(is_string($supplier) ? $supplier : null);
        if ($supplierName === null) {
            return ['supplier_id' => null, 'supplier_name' => null];
        }

        if ($this->suppliers !== null) {
            $supplierRecord = $this->suppliers->findByName($supplierName);
            if ($supplierRecord !== null) {
                return [
                    'supplier_id'   => (int) ($supplierRecord['id'] ?? 0),
                    'supplier_name' => $this->nullableText((string) ($supplierRecord['supplier_name'] ?? '')),
                ];
            }
        }

        return ['supplier_id' => null, 'supplier_name' => $supplierName];
    }
}
