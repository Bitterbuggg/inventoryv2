<?php

namespace App\Repositories\Contracts\Inventory;

interface StockMovementRepositoryInterface
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
    public function findByNumber(string $movementNumber): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;
}
