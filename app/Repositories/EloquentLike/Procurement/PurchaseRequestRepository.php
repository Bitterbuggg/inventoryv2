<?php

namespace App\Repositories\EloquentLike\Procurement;

use App\Models\Procurement\PurchaseRequestItemModel;
use App\Models\Procurement\PurchaseRequestModel;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use RuntimeException;

class PurchaseRequestRepository implements PurchaseRequestRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newRequestModel();

        if (! empty($filters['status'])) {
            $model->where('status', (string) $filters['status']);
        }

        if (! empty($filters['requested_by'])) {
            $model->where('requested_by', (int) $filters['requested_by']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newRequestModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNumber(string $prNumber): ?array
    {
        $record = $this->newRequestModel()->where('pr_number', $prNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newRequestModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create purchase request.');
        }

        return (int) $id;
    }

    public function addItems(int $purchaseRequestId, array $items): void
    {
        if ($items === []) {
            return;
        }

        $rows = [];

        foreach ($items as $item) {
            $row = [
                'purchase_request_id' => $purchaseRequestId,
                'item_name'           => $item['item_name'] ?? '',
                'requested_qty'       => $item['requested_qty'] ?? 0,
                'approved_qty'        => $item['approved_qty'] ?? null,
                'unit'                => $item['unit'] ?? 'unit',
                'estimated_unit_cost' => $item['estimated_unit_cost'] ?? null,
                'notes'               => $item['notes'] ?? null,
            ];

            if (($item['product_id'] ?? null) !== null) {
                $row['product_id'] = $item['product_id'];
            }

            $rows[] = $row;
        }

        $this->newItemModel()->insertBatch($rows);
    }

    public function replaceItems(int $purchaseRequestId, array $items): void
    {
        $itemModel = $this->newItemModel();
        $itemModel->where('purchase_request_id', $purchaseRequestId)->delete();

        $this->addItems($purchaseRequestId, $items);
    }

    public function listItems(int $purchaseRequestId): array
    {
        return $this->newItemModel()
            ->where('purchase_request_id', $purchaseRequestId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function update(int $id, array $data): bool
    {
        return $this->newRequestModel()->update($id, $data);
    }

    private function newRequestModel(): PurchaseRequestModel
    {
        return new PurchaseRequestModel();
    }

    private function newItemModel(): PurchaseRequestItemModel
    {
        return new PurchaseRequestItemModel();
    }
}


