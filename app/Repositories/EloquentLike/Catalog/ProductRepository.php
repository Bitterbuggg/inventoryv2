<?php

namespace App\Repositories\EloquentLike\Catalog;

use App\Models\Catalog\ProductModel;
use App\Repositories\Contracts\Catalog\ProductRepositoryInterface;
use RuntimeException;

class ProductRepository implements ProductRepositoryInterface
{
    public function listAll(bool $activeOnly = false): array
    {
        $model = $this->newModel();

        if ($activeOnly) {
            $model->where('is_active', 1);
        }

        return $model->orderBy('product_name', 'ASC')->orderBy('unit', 'ASC')->findAll();
    }

    public function listAvailableForIssuance(): array
    {
        return $this->newModel()
            ->select('products.*, SUM(inventory_stocks.available_qty) AS available_qty')
            ->join('inventory_stocks', 'inventory_stocks.product_id = products.id', 'inner')
            ->where('products.is_active', 1)
            ->where('inventory_stocks.available_qty >', 0)
            ->groupBy('products.id')
            ->orderBy('products.product_name', 'ASC')
            ->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNameAndUnit(string $productName, string $unit): ?array
    {
        $record = $this->newModel()
            ->where('product_name', $productName)
            ->where('unit', $unit)
            ->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create product.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): ProductModel
    {
        return new ProductModel();
    }
}
