<?php

namespace App\Repositories\Contracts\Catalog;

interface SupplierRepositoryInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(bool $activeOnly = false, ?string $keyword = null): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByName(string $supplierName): ?array;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;
}
