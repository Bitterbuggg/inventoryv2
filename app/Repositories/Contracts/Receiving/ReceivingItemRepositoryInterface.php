<?php

namespace App\Repositories\Contracts\Receiving;

interface ReceivingItemRepositoryInterface
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function addItems(int $receivingId, array $items): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listByReceiving(int $receivingId): array;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;

    public function sumAcceptedForPoItem(int $purchaseOrderItemId): float;
}
