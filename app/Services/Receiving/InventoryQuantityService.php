<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Receiving\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Receiving\StockMovementRepositoryInterface;

class InventoryQuantityService
{
    public function __construct(
        private readonly InventoryStockRepositoryInterface $inventoryStocks,
        private readonly StockMovementRepositoryInterface $stockMovements,
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
}
