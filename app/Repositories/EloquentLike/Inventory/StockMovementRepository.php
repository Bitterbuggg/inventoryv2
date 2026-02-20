<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Models\Inventory\StockMovementModel;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;
use RuntimeException;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['reference_type'])) {
            $model->where('reference_type', (string) $filters['reference_type']);
        }

        if (! empty($filters['reference_id'])) {
            $model->where('reference_id', (int) $filters['reference_id']);
        }

        if (! empty($filters['inventory_stock_id'])) {
            $model->where('inventory_stock_id', (int) $filters['inventory_stock_id']);
        }

        if (! empty($filters['movement_type'])) {
            $model->where('movement_type', (string) $filters['movement_type']);
        }

        if (! empty($filters['date_from'])) {
            $model->where('performed_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (! empty($filters['date_to'])) {
            $model->where('performed_at <=', $filters['date_to'] . ' 23:59:59');
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function findByNumber(string $movementNumber): ?array
    {
        $record = $this->newModel()->where('movement_number', $movementNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create stock movement record.');
        }

        return (int) $id;
    }

    private function newModel(): StockMovementModel
    {
        return new StockMovementModel();
    }
}
