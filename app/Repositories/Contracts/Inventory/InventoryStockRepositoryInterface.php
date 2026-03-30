<?php

namespace App\Repositories\Contracts\Inventory;

interface InventoryStockRepositoryInterface
{
    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function list(array $filters = []): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByKey(string $itemName, string $unit, ?string $batchNo, ?string $lotNo, ?string $expiryDate): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listForAllocation(string $itemName, string $unit): array;
}
