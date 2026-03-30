<?php

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\StockMovementService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class InventoryPostingServiceTest extends CIUnitTestCase
{
    public function testPostReceivingItemsUpdatesExistingStockUsingWeightedAverageCost(): void
    {
        $inventoryStocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $purchaseOrders  = $this->createMock(PurchaseOrderRepositoryInterface::class);
        $movements       = $this->createMock(StockMovementService::class);

        $purchaseOrders->method('findItem')->with(12)->willReturn([
            'id'           => 12,
            'ordered_qty'  => 20,
            'received_qty' => 5,
        ]);

        $purchaseOrders->expects($this->once())
            ->method('updateItem')
            ->with(12, ['received_qty' => 8.0]);

        $inventoryStocks->method('findByKey')
            ->with('Paracetamol', 'box', 'B-1', 'L-1', '2027-12-31')
            ->willReturn([
                'id'                => 33,
                'on_hand_qty'       => 10,
                'reserved_qty'      => 2,
                'average_unit_cost' => 20,
            ]);

        $inventoryStocks->expects($this->once())
            ->method('update')
            ->with(
                33,
                $this->callback(static function (array $data): bool {
                    return (float) ($data['on_hand_qty'] ?? -1) === 13.0
                        && (float) ($data['available_qty'] ?? -1) === 11.0
                        && (float) ($data['average_unit_cost'] ?? -1) === 17.69
                        && array_key_exists('last_movement_at', $data);
                }),
            );

        $movements->expects($this->once())
            ->method('recordReceivingMovement')
            ->with($this->callback(static fn (array $data): bool =>
                (int) ($data['reference_id'] ?? 0) === 501
                && (int) ($data['inventory_stock_id'] ?? 0) === 33
                && (float) ($data['qty_in'] ?? 0) === 3.0
                && (float) ($data['balance_after'] ?? 0) === 13.0
                && (float) ($data['unit_cost'] ?? 0) === 10.0
            ));

        $service = new InventoryPostingService($inventoryStocks, $purchaseOrders, $movements);

        $service->postReceivingItems(501, [[
            'purchase_order_item_id' => 12,
            'item_name'              => 'Paracetamol',
            'unit'                   => 'box',
            'accepted_qty'           => 3,
            'batch_no'               => 'B-1',
            'lot_no'                 => 'L-1',
            'expiry_date'            => '2027-12-31',
            'unit_cost'              => 10,
            'remarks'                => 'QA passed',
        ]], 9);

        $this->assertTrue(true);
    }
}
