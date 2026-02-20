<?php

namespace App\Repositories\Contracts\Inventory;

interface IssuanceItemRepositoryInterface
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function addItems(int $issuanceId, array $items): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listByIssuance(int $issuanceId): array;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
