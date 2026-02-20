<?php

namespace App\Repositories\Contracts\Procurement;

interface PurchaseOrderRepositoryInterface
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
    public function findByNumber(string $poNumber): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByPurchaseRequest(int $purchaseRequestId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function addItems(int $purchaseOrderId, array $items): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listItems(int $purchaseOrderId): array;

    /**
     * @return array<string, mixed>|null
     */
    public function findItem(int $purchaseOrderItemId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function updateItem(int $purchaseOrderItemId, array $data): bool;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
