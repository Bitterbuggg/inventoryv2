<?php

namespace App\Repositories\Contracts\Inventory;

interface IssuanceItemAllocationRepositoryInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listByIssuance(int $issuanceId): array;
}
