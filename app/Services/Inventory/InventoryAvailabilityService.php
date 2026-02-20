<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;

class InventoryAvailabilityService
{
    public function __construct(private readonly InventoryStockRepositoryInterface $inventoryStocks)
    {
    }

    public function hasSufficientStock(string $itemName, string $unit, float $requiredQty): bool
    {
        $available = 0.0;

        foreach ($this->inventoryStocks->listForAllocation($itemName, $unit) as $stock) {
            $available += (float) ($stock['available_qty'] ?? 0);

            if ($available + 0.0005 >= $requiredQty) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, array{stock: array<string, mixed>, qty: float}>
     */
    public function allocate(string $itemName, string $unit, float $requiredQty): array
    {
        if ($requiredQty <= 0) {
            throw new \InvalidArgumentException('Required quantity must be greater than zero.');
        }

        $stocks      = $this->inventoryStocks->listForAllocation($itemName, $unit);
        $remaining   = $requiredQty;
        $allocations = [];

        foreach ($stocks as $stock) {
            $availableQty = (float) ($stock['available_qty'] ?? 0);

            if ($availableQty <= 0) {
                continue;
            }

            $allocateQty = min($remaining, $availableQty);

            $allocations[] = [
                'stock' => $stock,
                'qty'   => $allocateQty,
            ];

            $remaining -= $allocateQty;

            if ($remaining <= 0.0005) {
                break;
            }
        }

        if ($remaining > 0.0005) {
            throw new \DomainException('Insufficient stock for item ' . $itemName . '.');
        }

        return $allocations;
    }
}
