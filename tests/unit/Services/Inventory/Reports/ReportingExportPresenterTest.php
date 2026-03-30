<?php

use App\Services\Inventory\Reports\ReportingExportPresenter;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReportingExportPresenterTest extends CIUnitTestCase
{
    public function testStockMovementsCsvMapsLabelsAndColumns(): void
    {
        $presenter = new ReportingExportPresenter();

        $csv = $presenter->stockMovementsCsv([
            [
                'id' => 7,
                'movement_number' => 'MOV-100',
                'movement_type' => 'adjustment_out',
                'reference_type' => 'manual_adjustment',
                'reference_id' => 55,
                'item_name' => 'Bandage',
                'unit' => 'pack',
                'qty_in' => 0,
                'qty_out' => 2,
                'balance_after' => 8,
                'performed_at' => '2026-03-30 10:00:00',
            ],
        ]);

        $this->assertSame('ID', $csv['headers'][0]);
        $this->assertSame('Stock Disposal', $csv['rows'][0][2]);
        $this->assertSame('Stock Disposal', $csv['rows'][0][3]);
        $this->assertSame('Bandage', $csv['rows'][0][5]);
    }

    public function testLowStockCsvFiltersRowsByThreshold(): void
    {
        $presenter = new ReportingExportPresenter();

        $csv = $presenter->lowStockCsv([
            ['id' => 1, 'item_name' => 'A', 'unit' => 'box', 'available_qty' => 5, 'on_hand_qty' => 5, 'reserved_qty' => 0],
            ['id' => 2, 'item_name' => 'B', 'unit' => 'box', 'available_qty' => 12, 'on_hand_qty' => 12, 'reserved_qty' => 0],
        ], 10);

        $this->assertCount(1, $csv['rows']);
        $this->assertSame('1', $csv['rows'][0][0]);
    }

    public function testFastMovingCsvAddsRankAndFormatsQuantity(): void
    {
        $presenter = new ReportingExportPresenter();

        $csv = $presenter->fastMovingCsv([
            ['item_name' => 'Paracetamol', 'unit' => 'box', 'total_qty_out' => 12],
            ['item_name' => 'Bandage', 'unit' => 'pack', 'total_qty_out' => 3.5],
        ]);

        $this->assertSame(['Rank', 'Item', 'Unit', 'Total Qty Out'], $csv['headers']);
        $this->assertSame('1', $csv['rows'][0][0]);
        $this->assertSame('2', $csv['rows'][1][0]);
        $this->assertSame('3.50', $csv['rows'][1][3]);
    }
}
