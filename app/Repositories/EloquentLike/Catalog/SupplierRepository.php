<?php

namespace App\Repositories\EloquentLike\Catalog;

use App\Models\Catalog\SupplierModel;
use App\Repositories\Contracts\Catalog\SupplierRepositoryInterface;
use RuntimeException;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function listAll(bool $activeOnly = false, ?string $keyword = null): array
    {
        $model = $this->newModel();

        if ($activeOnly) {
            $model->where('is_active', 1);
        }

        $keyword = trim((string) $keyword);
        if ($keyword !== '') {
            $model->groupStart()
                ->like('supplier_code', $keyword)
                ->orLike('supplier_name', $keyword)
                ->orLike('contact_person', $keyword)
                ->orLike('phone', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }

        return $model->orderBy('supplier_name', 'ASC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByName(string $supplierName): ?array
    {
        $record = $this->newModel()
            ->where('supplier_name', $supplierName)
            ->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create supplier.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): SupplierModel
    {
        return new SupplierModel();
    }
}
