<?php

namespace App\Repositories\EloquentLike\Receiving;

use App\Models\Inventory\InventoryStockModel;
use App\Repositories\Contracts\Receiving\InventoryStockRepositoryInterface;
use RuntimeException;

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

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create inventory stock record.');
        }

        return (int) $id;
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
