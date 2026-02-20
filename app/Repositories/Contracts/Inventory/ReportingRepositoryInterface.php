<?php

namespace App\Repositories\Contracts\Inventory;

interface ReportingRepositoryInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockBalanceReport(?string $keyword = null): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockMovementReport(?string $dateFrom = null, ?string $dateTo = null, ?string $movementType = null): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function issuanceReport(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function lowStockReport(float $threshold = 10): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fastMovingReport(?string $dateFrom = null, ?string $dateTo = null, int $limit = 20): array;
}
