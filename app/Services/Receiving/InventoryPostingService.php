<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;

class InventoryPostingService
{
    public function __construct(
        private readonly InventoryStockRepositoryInterface $inventoryStocks,
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
        private readonly StockMovementService $stockMovements,
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $receivingItems
     */
    public function postReceivingItems(int $receivingId, array $receivingItems, int $actorId): void
    {
        foreach ($receivingItems as $receivingItem) {
            $purchaseOrderItemId = (int) ($receivingItem['purchase_order_item_id'] ?? 0);
            $acceptedQty         = (float) ($receivingItem['accepted_qty'] ?? 0);

            if ($purchaseOrderItemId <= 0 || $acceptedQty <= 0) {
                continue;
            }

            $purchaseOrderItem = $this->purchaseOrders->findItem($purchaseOrderItemId);

            if ($purchaseOrderItem === null) {
                throw new \DomainException('Purchase order item not found during posting.');
            }

            $orderedQty      = (float) ($purchaseOrderItem['ordered_qty'] ?? 0);
            $alreadyReceived = (float) ($purchaseOrderItem['received_qty'] ?? 0);
            $remainingQty    = $orderedQty - $alreadyReceived;

            if ($acceptedQty > $remainingQty + 0.0005) {
                throw new \DomainException('Accepted quantity exceeds remaining purchase order quantity.');
            }

            $updatedReceivedQty = $alreadyReceived + $acceptedQty;

            $this->purchaseOrders->updateItem($purchaseOrderItemId, [
                'received_qty' => $updatedReceivedQty,
            ]);

            $productId  = (int) ($receivingItem['product_id'] ?? $purchaseOrderItem['product_id'] ?? 0) ?: null;
            $itemName   = (string) ($receivingItem['item_name'] ?? '');
            $unit       = (string) ($receivingItem['unit'] ?? 'unit');
            $batchNo    = $this->nullableString($receivingItem['batch_no'] ?? null);
            $lotNo      = $this->nullableString($receivingItem['lot_no'] ?? null);
            $expiryDate = $this->nullableString($receivingItem['expiry_date'] ?? null);
            $unitCost   = (float) ($receivingItem['unit_cost'] ?? 0);

            $stock = $this->inventoryStocks->findByKey($itemName, $unit, $batchNo, $lotNo, $expiryDate);

            if ($stock === null) {
                $createData = [
                    'item_name'         => $itemName,
                    'unit'              => $unit,
                    'batch_no'          => $batchNo,
                    'lot_no'            => $lotNo,
                    'expiry_date'       => $expiryDate,
                    'on_hand_qty'       => $acceptedQty,
                    'reserved_qty'      => 0,
                    'available_qty'     => $acceptedQty,
                    'average_unit_cost' => $unitCost,
                    'last_movement_at'  => date('Y-m-d H:i:s'),
                ];

                if ($productId !== null) {
                    $createData['product_id'] = $productId;
                }

                $inventoryStockId = $this->inventoryStocks->create($createData);

                $balanceAfter = $acceptedQty;
            } else {
                $inventoryStockId = (int) ($stock['id'] ?? 0);
                $oldOnHandQty     = (float) ($stock['on_hand_qty'] ?? 0);
                $reservedQty      = (float) ($stock['reserved_qty'] ?? 0);
                $oldAverageCost   = (float) ($stock['average_unit_cost'] ?? 0);

                $newOnHandQty   = $oldOnHandQty + $acceptedQty;
                $newAverageCost = $newOnHandQty > 0
                    ? (($oldOnHandQty * $oldAverageCost) + ($acceptedQty * $unitCost)) / $newOnHandQty
                    : 0;
                $newAvailableQty = $newOnHandQty - $reservedQty;

                $updateData = [
                    'on_hand_qty'       => $newOnHandQty,
                    'available_qty'     => $newAvailableQty,
                    'average_unit_cost' => round($newAverageCost, 2),
                    'last_movement_at'  => date('Y-m-d H:i:s'),
                ];

                if ($productId !== null || ($stock['product_id'] ?? null) !== null) {
                    $updateData['product_id'] = $productId ?? $stock['product_id'];
                }

                $this->inventoryStocks->update($inventoryStockId, $updateData);

                $balanceAfter = $newOnHandQty;
            }

            $this->stockMovements->recordReceivingMovement([
                'reference_id'       => $receivingId,
                'product_id'         => $productId,
                'item_name'          => $itemName,
                'inventory_stock_id' => $inventoryStockId,
                'unit'               => $unit,
                'qty_in'             => $acceptedQty,
                'balance_after'      => $balanceAfter,
                'unit_cost'          => $unitCost,
                'performed_by'       => $actorId,
                'remarks'            => $receivingItem['remarks'] ?? null,
            ]);
        }
    }

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
