<?php

namespace App\Repositories\Contracts\Shared;

interface AuditLogRepositoryInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int;

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function list(array $filters = []): array;
}
