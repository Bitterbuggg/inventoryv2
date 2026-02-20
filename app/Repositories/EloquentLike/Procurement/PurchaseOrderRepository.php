<?php

namespace App\Repositories\EloquentLike\Procurement;

use App\Models\Procurement\PurchaseOrderItemModel;
use App\Models\Procurement\PurchaseOrderModel;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use RuntimeException;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newOrderModel();

        if (! empty($filters['status'])) {
            $model->where('status', (string) $filters['status']);
        }

        if (! empty($filters['purchase_request_id'])) {
            $model->where('purchase_request_id', (int) $filters['purchase_request_id']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newOrderModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNumber(string $poNumber): ?array
    {
        $record = $this->newOrderModel()->where('po_number', $poNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function findByPurchaseRequest(int $purchaseRequestId): ?array
    {
        $record = $this->newOrderModel()->where('purchase_request_id', $purchaseRequestId)->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newOrderModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create purchase order.');
        }

        return (int) $id;
    }

    public function addItems(int $purchaseOrderId, array $items): void
    {
        if ($items === []) {
            return;
        }

        $rows = [];

        foreach ($items as $item) {
            $rows[] = [
                'purchase_order_id'        => $purchaseOrderId,
                'purchase_request_item_id' => $item['purchase_request_item_id'] ?? null,
                'item_name'                => $item['item_name'] ?? '',
                'unit'                     => $item['unit'] ?? 'unit',
                'ordered_qty'              => $item['ordered_qty'] ?? 0,
                'received_qty'             => $item['received_qty'] ?? 0,
                'unit_cost'                => $item['unit_cost'] ?? 0,
                'line_total'               => $item['line_total'] ?? 0,
            ];
        }

        $this->newItemModel()->insertBatch($rows);
    }

    public function listItems(int $purchaseOrderId): array
    {
        return $this->newItemModel()
            ->where('purchase_order_id', $purchaseOrderId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findItem(int $purchaseOrderItemId): ?array
    {
        $record = $this->newItemModel()->find($purchaseOrderItemId);

        return is_array($record) ? $record : null;
    }

    public function updateItem(int $purchaseOrderItemId, array $data): bool
    {
        return $this->newItemModel()->update($purchaseOrderItemId, $data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->newOrderModel()->update($id, $data);
    }

    private function newOrderModel(): PurchaseOrderModel
    {
        return new PurchaseOrderModel();
    }

    private function newItemModel(): PurchaseOrderItemModel
    {
        return new PurchaseOrderItemModel();
    }
}
