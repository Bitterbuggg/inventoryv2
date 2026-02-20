<?php

namespace App\Repositories\EloquentLike\Procurement;

use App\Models\Procurement\PoRequestModel;
use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use RuntimeException;

class PoRequestRepository implements PoRequestRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['status'])) {
            $model->where('status', (string) $filters['status']);
        }

        if (! empty($filters['purchase_order_id'])) {
            $model->where('purchase_order_id', (int) $filters['purchase_order_id']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNumber(string $poRequestNumber): ?array
    {
        $record = $this->newModel()->where('po_request_number', $poRequestNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function findByPurchaseOrder(int $purchaseOrderId): ?array
    {
        $record = $this->newModel()->where('purchase_order_id', $purchaseOrderId)->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create PO request.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): PoRequestModel
    {
        return new PoRequestModel();
    }
}
