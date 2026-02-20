<?php

namespace App\Repositories\Contracts\Procurement;

interface PurchaseRequestRepositoryInterface
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
    public function findByNumber(string $prNumber): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function addItems(int $purchaseRequestId, array $items): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listItems(int $purchaseRequestId): array;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
