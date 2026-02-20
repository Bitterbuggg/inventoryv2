<?php

namespace App\Repositories\Contracts\Procurement;

interface PoRequestRepositoryInterface
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
    public function findByNumber(string $poRequestNumber): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByPurchaseOrder(int $purchaseOrderId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
