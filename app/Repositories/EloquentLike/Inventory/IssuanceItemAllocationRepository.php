<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Models\Inventory\IssuanceItemAllocationModel;
use App\Repositories\Contracts\Inventory\IssuanceItemAllocationRepositoryInterface;
use RuntimeException;

class IssuanceItemAllocationRepository implements IssuanceItemAllocationRepositoryInterface
{
    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create issuance allocation record.');
        }

        return (int) $id;
    }

    public function listByIssuance(int $issuanceId): array
    {
        return $this->newModel()
            ->where('issuance_id', $issuanceId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    private function newModel(): IssuanceItemAllocationModel
    {
        return new IssuanceItemAllocationModel();
    }
}
