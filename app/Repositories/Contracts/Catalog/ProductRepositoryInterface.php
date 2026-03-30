<?php

namespace App\Repositories\Contracts\Catalog;

interface ProductRepositoryInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(bool $activeOnly = false): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAvailableForIssuance(): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByNameAndUnit(string $productName, string $unit): ?array;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;
}
