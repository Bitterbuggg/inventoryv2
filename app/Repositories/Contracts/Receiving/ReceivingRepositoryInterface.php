<?php

namespace App\Repositories\Contracts\Receiving;

interface ReceivingRepositoryInterface
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
    public function findByNumber(string $receivingNumber): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findByPoRequest(int $poRequestId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;
}
