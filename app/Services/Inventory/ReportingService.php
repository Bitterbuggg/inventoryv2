<?php

namespace App\Services\Inventory;

use App\Services\Inventory\Reports\FastMovingReportReadModel;
use App\Services\Inventory\Reports\IssuanceReportReadModel;
use App\Services\Inventory\Reports\LowStockReportReadModel;
use App\Services\Inventory\Reports\StockBalanceReportReadModel;
use App\Services\Inventory\Reports\StockMovementReportReadModel;

class ReportingService
{
    public function __construct(
        private readonly StockBalanceReportReadModel $stockBalanceReport,
        private readonly StockMovementReportReadModel $stockMovementReport,
        private readonly IssuanceReportReadModel $issuanceReport,
        private readonly LowStockReportReadModel $lowStockReport,
        private readonly FastMovingReportReadModel $fastMovingReport,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockBalance(?string $keyword = null): array
    {
        $keyword = trim((string) $keyword);

        return $this->stockBalanceReport->list($keyword === '' ? null : $keyword);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function stockMovements(?string $dateFrom = null, ?string $dateTo = null, ?string $movementType = null): array
    {
        return $this->stockMovementReport->list(
            $this->nullableFilter($dateFrom),
            $this->nullableFilter($dateTo),
            $this->nullableFilter($movementType),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function issuances(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->issuanceReport->list(
            $this->nullableFilter($status),
            $this->nullableFilter($dateFrom),
            $this->nullableFilter($dateTo),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function lowStock(float $threshold = 10): array
    {
        return $this->lowStockReport->list(max(0, $threshold));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fastMoving(?string $dateFrom = null, ?string $dateTo = null, int $limit = 20): array
    {
        return $this->fastMovingReport->list(
            $this->nullableFilter($dateFrom),
            $this->nullableFilter($dateTo),
            max(1, $limit),
        );
    }

    private function nullableFilter(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
