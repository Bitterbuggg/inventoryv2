<?php

use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;
use App\Services\Receiving\StockMovementService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class StockMovementServiceTest extends CIUnitTestCase
{
    public function testRecordAdjustmentOutMovementCreatesExpectedPayload(): void
    {
        $repository = $this->createMock(StockMovementRepositoryInterface::class);

        $repository->method('findByNumber')->willReturn(null);
        $repository->expects($this->once())
            ->method('create')
            ->with($this->callback(static function (array $data): bool {
                return str_starts_with((string) ($data['movement_number'] ?? ''), 'MOVADJ-')
                    && ($data['movement_type'] ?? null) === 'adjustment_out'
                    && ($data['reference_type'] ?? null) === 'manual_adjustment'
                    && (float) ($data['qty_out'] ?? 0) === 2.0
                    && (int) ($data['product_id'] ?? 0) === 14
                    && ($data['remarks'] ?? null) === 'Stock Disposal: Expired';
            }))
            ->willReturn(10);

        $service = new StockMovementService($repository);

        $movementId = $service->recordAdjustmentOutMovement([
            'product_id'         => 14,
            'item_name'          => 'Paracetamol',
            'inventory_stock_id' => 99,
            'unit'               => 'box',
            'qty_out'            => 2,
            'balance_after'      => 8,
            'unit_cost'          => 12.5,
            'performed_by'       => 7,
            'reason'             => 'Expired',
        ]);

        $this->assertSame(10, $movementId);
    }

    public function testRecordIssuanceMovementCreatesExpectedPayload(): void
    {
        $repository = $this->createMock(StockMovementRepositoryInterface::class);

        $repository->method('findByNumber')->willReturn(null);
        $repository->expects($this->once())
            ->method('create')
            ->with($this->callback(static function (array $data): bool {
                return str_starts_with((string) ($data['movement_number'] ?? ''), 'MOVOUT-')
                    && ($data['movement_type'] ?? null) === 'issuance'
                    && ($data['reference_type'] ?? null) === 'issuance'
                    && (int) ($data['reference_id'] ?? 0) === 501
                    && (float) ($data['qty_out'] ?? 0) === 5.0
                    && ($data['remarks'] ?? null) === 'Issuance release';
            }))
            ->willReturn(11);

        $service = new StockMovementService($repository);

        $movementId = $service->recordIssuanceMovement([
            'reference_id'       => 501,
            'item_name'          => 'Bandage',
            'inventory_stock_id' => 123,
            'unit'               => 'pack',
            'qty_out'            => 5,
            'balance_after'      => 15,
            'unit_cost'          => 3.25,
            'performed_by'       => 9,
        ]);

        $this->assertSame(11, $movementId);
    }
}
