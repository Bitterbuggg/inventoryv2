<?php

namespace App\Repositories\EloquentLike\Procurement;

use App\Models\Procurement\ApprovalModel;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use RuntimeException;

class ApprovalRepository implements ApprovalRepositoryInterface
{
    public function listPending(): array
    {
        return $this->newModel()
            ->where('decision', 'pending')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findPendingByReference(string $referenceType, int $referenceId): ?array
    {
        $record = $this->newModel()
            ->where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->where('decision', 'pending')
            ->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create approval record.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): ApprovalModel
    {
        return new ApprovalModel();
    }
}
