<?php

namespace App\Repositories\Contracts\Procurement;

interface ApprovalRepositoryInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPending(): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findPendingByReference(string $referenceType, int $referenceId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
