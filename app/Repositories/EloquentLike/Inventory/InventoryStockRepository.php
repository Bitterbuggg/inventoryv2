<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Models\Inventory\InventoryStockModel;
use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;

class InventoryStockRepository implements InventoryStockRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['item_name'])) {
            $model->like('item_name', (string) $filters['item_name']);
        }

        return $model->orderBy('item_name', 'ASC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByKey(string $itemName, string $unit, ?string $batchNo, ?string $lotNo, ?string $expiryDate): ?array
    {
        $model = $this->newModel()
            ->where('item_name', $itemName)
            ->where('unit', $unit);

        if ($batchNo === null || $batchNo === '') {
            $model->where('batch_no', null);
        } else {
            $model->where('batch_no', $batchNo);
        }

        if ($lotNo === null || $lotNo === '') {
            $model->where('lot_no', null);
        } else {
            $model->where('lot_no', $lotNo);
        }

        if ($expiryDate === null || $expiryDate === '') {
            $model->where('expiry_date', null);
        } else {
            $model->where('expiry_date', $expiryDate);
        }

        $record = $model->first();

        return is_array($record) ? $record : null;
    }

    public function listForAllocation(string $itemName, string $unit): array
    {
        return $this->newModel()
            ->where('item_name', $itemName)
            ->where('unit', $unit)
            ->where('available_qty >', 0)
            ->orderBy('CASE WHEN expiry_date IS NULL THEN 1 ELSE 0 END', 'ASC', false)
            ->orderBy('expiry_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): InventoryStockModel
    {
        return new InventoryStockModel();
    }
}
