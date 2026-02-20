<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Models\Inventory\IssuanceModel;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use RuntimeException;

class IssuanceRepository implements IssuanceRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['status'])) {
            $model->where('status', (string) $filters['status']);
        }

        if (! empty($filters['requestor_id'])) {
            $model->where('requestor_id', (int) $filters['requestor_id']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNumber(string $issuanceNumber): ?array
    {
        $record = $this->newModel()->where('issuance_number', $issuanceNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create issuance record.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): IssuanceModel
    {
        return new IssuanceModel();
    }
}
