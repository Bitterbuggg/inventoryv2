<?php

use App\Services\Inventory\ReportingService;
use App\Services\Inventory\Reports\FastMovingReportReadModel;
use App\Services\Inventory\Reports\IssuanceReportReadModel;
use App\Services\Inventory\Reports\LowStockReportReadModel;
use App\Services\Inventory\Reports\StockBalanceReportReadModel;
use App\Services\Inventory\Reports\StockMovementReportReadModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReportingServiceTest extends CIUnitTestCase
{
    public function testStockBalanceNormalizesBlankKeyword(): void
    {
        $stockBalance = $this->createMock(StockBalanceReportReadModel::class);
        $stockMovements = $this->createMock(StockMovementReportReadModel::class);
        $issuances = $this->createMock(IssuanceReportReadModel::class);
        $lowStock = $this->createMock(LowStockReportReadModel::class);
        $fastMoving = $this->createMock(FastMovingReportReadModel::class);

        $stockBalance->expects($this->once())
            ->method('list')
            ->with(null)
            ->willReturn([['id' => 1]]);

        $service = new ReportingService($stockBalance, $stockMovements, $issuances, $lowStock, $fastMoving);

        $result = $service->stockBalance('   ');

        $this->assertCount(1, $result);
    }

    public function testStockMovementsNormalizesFilters(): void
    {
        $stockBalance = $this->createMock(StockBalanceReportReadModel::class);
        $stockMovements = $this->createMock(StockMovementReportReadModel::class);
        $issuances = $this->createMock(IssuanceReportReadModel::class);
        $lowStock = $this->createMock(LowStockReportReadModel::class);
        $fastMoving = $this->createMock(FastMovingReportReadModel::class);

        $stockMovements->expects($this->once())
            ->method('list')
            ->with('2026-01-01', null, 'issuance')
            ->willReturn([['id' => 2]]);

        $service = new ReportingService($stockBalance, $stockMovements, $issuances, $lowStock, $fastMoving);

        $result = $service->stockMovements(' 2026-01-01 ', ' ', ' issuance ');

        $this->assertCount(1, $result);
    }

    public function testIssuancesNormalizesFilters(): void
    {
        $stockBalance = $this->createMock(StockBalanceReportReadModel::class);
        $stockMovements = $this->createMock(StockMovementReportReadModel::class);
        $issuances = $this->createMock(IssuanceReportReadModel::class);
        $lowStock = $this->createMock(LowStockReportReadModel::class);
        $fastMoving = $this->createMock(FastMovingReportReadModel::class);

        $issuances->expects($this->once())
            ->method('list')
            ->with('released', '2026-01-01', '2026-12-31')
            ->willReturn([['id' => 3]]);

        $service = new ReportingService($stockBalance, $stockMovements, $issuances, $lowStock, $fastMoving);

        $result = $service->issuances(' released ', ' 2026-01-01 ', ' 2026-12-31 ');

        $this->assertCount(1, $result);
    }

    public function testLowStockClampsThresholdToZeroOrGreater(): void
    {
        $stockBalance = $this->createMock(StockBalanceReportReadModel::class);
        $stockMovements = $this->createMock(StockMovementReportReadModel::class);
        $issuances = $this->createMock(IssuanceReportReadModel::class);
        $lowStock = $this->createMock(LowStockReportReadModel::class);
        $fastMoving = $this->createMock(FastMovingReportReadModel::class);

        $lowStock->expects($this->once())
            ->method('list')
            ->with(0.0)
            ->willReturn([['id' => 4]]);

        $service = new ReportingService($stockBalance, $stockMovements, $issuances, $lowStock, $fastMoving);

        $result = $service->lowStock(-25);

        $this->assertCount(1, $result);
    }

    public function testFastMovingClampsLimitAndNormalizesDates(): void
    {
        $stockBalance = $this->createMock(StockBalanceReportReadModel::class);
        $stockMovements = $this->createMock(StockMovementReportReadModel::class);
        $issuances = $this->createMock(IssuanceReportReadModel::class);
        $lowStock = $this->createMock(LowStockReportReadModel::class);
        $fastMoving = $this->createMock(FastMovingReportReadModel::class);

        $fastMoving->expects($this->once())
            ->method('list')
            ->with('2026-01-01', null, 1)
            ->willReturn([['item_name' => 'Bandage']]);

        $service = new ReportingService($stockBalance, $stockMovements, $issuances, $lowStock, $fastMoving);

        $result = $service->fastMoving(' 2026-01-01 ', '', 0);

        $this->assertCount(1, $result);
    }
}
