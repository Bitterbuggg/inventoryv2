<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\ReportingRepositoryInterface;

class ReportingService
{
    public function __construct(private readonly ReportingRepositoryInterface $reports)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockBalance(?string $keyword = null): array
    {
        return $this->reports->stockBalanceReport($keyword);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockMovements(?string $dateFrom = null, ?string $dateTo = null, ?string $movementType = null): array
    {
        return $this->reports->stockMovementReport($dateFrom, $dateTo, $movementType);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function issuances(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->reports->issuanceReport($status, $dateFrom, $dateTo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function lowStock(float $threshold = 10): array
    {
        return $this->reports->lowStockReport($threshold);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fastMoving(?string $dateFrom = null, ?string $dateTo = null, int $limit = 20): array
    {
        return $this->reports->fastMovingReport($dateFrom, $dateTo, $limit);
    }
}
