<?php

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;
use App\Services\Receiving\InventoryQuantityService;
use App\Services\Receiving\StockMovementService;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class InventoryQuantityServiceTest extends CIUnitTestCase
{
    public function testManualAdjustmentOutUpdatesStockAndDelegatesMovementRecording(): void
    {
        $inventoryStocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $stockMovements = $this->createMock(StockMovementRepositoryInterface::class);
        $movementRecorder = $this->createMock(StockMovementService::class);
        $db = $this->createMock(BaseConnection::class);

        $inventoryStocks->method('find')
            ->with(15)
            ->willReturn([
                'id'                => 15,
                'product_id'        => 44,
                'item_name'         => 'Paracetamol',
                'unit'              => 'box',
                'on_hand_qty'       => 10,
                'reserved_qty'      => 2,
                'average_unit_cost' => 5.5,
            ]);

        $inventoryStocks->expects($this->once())
            ->method('update')
            ->with(
                15,
                $this->callback(static function (array $data): bool {
                    return (float) ($data['on_hand_qty'] ?? 0) === 7.0
                        && (float) ($data['available_qty'] ?? 0) === 5.0
                        && array_key_exists('last_movement_at', $data);
                }),
            );

        $movementRecorder->expects($this->once())
            ->method('recordAdjustmentOutMovement')
            ->with($this->callback(static function (array $data): bool {
                return (int) ($data['product_id'] ?? 0) === 44
                    && (int) ($data['inventory_stock_id'] ?? 0) === 15
                    && (float) ($data['qty_out'] ?? 0) === 3.0
                    && (float) ($data['balance_after'] ?? 0) === 7.0
                    && (int) ($data['performed_by'] ?? 0) === 9
                    && ($data['reason'] ?? null) === 'Expired';
            }));

        $db->expects($this->once())->method('transBegin');
        $db->expects($this->once())->method('transCommit');
        $db->expects($this->never())->method('transRollback');

        $service = new InventoryQuantityService($inventoryStocks, $stockMovements, $movementRecorder, $db);
        $service->manualAdjustmentOut(15, 3, 9, 'Expired');
    }

    public function testManualAdjustmentOutRejectsQtyGreaterThanOnHand(): void
    {
        $inventoryStocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $stockMovements = $this->createMock(StockMovementRepositoryInterface::class);
        $movementRecorder = $this->createMock(StockMovementService::class);
        $db = $this->createMock(BaseConnection::class);

        $inventoryStocks->method('find')
            ->with(20)
            ->willReturn([
                'id'           => 20,
                'item_name'    => 'Bandage',
                'unit'         => 'pack',
                'on_hand_qty'  => 2,
                'reserved_qty' => 0,
            ]);

        $inventoryStocks->expects($this->never())->method('update');
        $movementRecorder->expects($this->never())->method('recordAdjustmentOutMovement');
        $db->expects($this->never())->method('transBegin');

        $service = new InventoryQuantityService($inventoryStocks, $stockMovements, $movementRecorder, $db);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('exceeds physical on-hand stock');

        $service->manualAdjustmentOut(20, 5, 9, 'Damaged');
    }
}
