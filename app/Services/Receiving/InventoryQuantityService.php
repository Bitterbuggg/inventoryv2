<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;

class InventoryQuantityService
{
    public function __construct(
        private readonly InventoryStockRepositoryInterface $inventoryStocks,
        private readonly StockMovementRepositoryInterface $stockMovements,
        private readonly StockMovementService $movementRecorder,
        private readonly \CodeIgniter\Database\BaseConnection $db,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $keyword = null): array
    {
        $filters = [];

        if ($keyword !== null && trim($keyword) !== '') {
            $filters['item_name'] = trim($keyword);
        }

        return $this->inventoryStocks->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithMovements(int $inventoryStockId): ?array
    {
        $stock = $this->inventoryStocks->find($inventoryStockId);

        if ($stock === null) {
            return null;
        }

        $stock['movements'] = $this->stockMovements->list([
            'inventory_stock_id' => $inventoryStockId,
        ]);

        return $stock;
    }

    public function manualAdjustmentOut(int $stockId, float $qty, int $actorId, string $reason): void
    {
        $stock = $this->inventoryStocks->find($stockId);
        if ($stock === null) {
            throw new \DomainException('Inventory stock record not found.');
        }

        $onHandQty = (float)($stock['on_hand_qty'] ?? 0);

        if ($qty <= 0) {
            throw new \DomainException('Adjustment quantity must be greater than zero.');
        }

        if ($qty > $onHandQty) {
            throw new \DomainException('Requested adjustment quantity exceeds physical on-hand stock.');
        }

        $this->db->transBegin();
        try {
            $newOnHand = $onHandQty - $qty;
            $reserved = (float)($stock['reserved_qty'] ?? 0);
            $newAvailable = max(0, $newOnHand - $reserved);

            $this->inventoryStocks->update($stockId, [
                'on_hand_qty' => $newOnHand,
                'available_qty' => $newAvailable,
                'last_movement_at' => date('Y-m-d H:i:s')
            ]);

            $this->movementRecorder->recordAdjustmentOutMovement([
                'product_id'         => (int) ($stock['product_id'] ?? 0) ?: null,
                'item_name'          => (string)$stock['item_name'],
                'inventory_stock_id' => $stockId,
                'unit'               => (string)$stock['unit'],
                'qty_out'            => $qty,
                'balance_after'      => $newOnHand,
                'unit_cost'          => (float)$stock['average_unit_cost'],
                'performed_by'       => $actorId,
                'reason'             => $reason,
            ]);

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}
