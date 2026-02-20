<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;
use App\Services\Shared\AuditService;
use CodeIgniter\Database\BaseConnection;

class IssuanceReleaseService
{
    public function __construct(
        private readonly IssuanceRepositoryInterface $issuances,
        private readonly IssuanceItemRepositoryInterface $issuanceItems,
        private readonly InventoryStockRepositoryInterface $inventoryStocks,
        private readonly StockMovementRepositoryInterface $stockMovements,
        private readonly InventoryAvailabilityService $availability,
        private readonly AuditService $audit,
        private readonly BaseConnection $db,
    ) {
    }

    public function release(int $issuanceId, int $actorId): void
    {
        $issuance = $this->issuances->find($issuanceId);

        if ($issuance === null) {
            throw new \DomainException('Issuance record not found.');
        }

        if (($issuance['status'] ?? '') !== 'approved') {
            throw new \DomainException('Only approved issuances can be released.');
        }

        $items = $this->issuanceItems->listByIssuance($issuanceId);

        if ($items === []) {
            throw new \DomainException('Issuance has no items to release.');
        }

        $releasedSummary = [
            'items_released' => 0,
            'total_qty_out'  => 0.0,
            'total_cost'     => 0.0,
        ];

        $this->db->transBegin();

        try {
            foreach ($items as $item) {
                $itemName     = (string) ($item['item_name'] ?? '');
                $unit         = (string) ($item['unit'] ?? 'unit');
                $requestedQty = (float) ($item['requested_qty'] ?? 0);

                if ($requestedQty <= 0) {
                    throw new \DomainException('Requested quantity must be greater than zero for all issuance lines.');
                }

                $allocations = $this->availability->allocate($itemName, $unit, $requestedQty);

                $issuedQty      = 0.0;
                $totalCost      = 0.0;
                $primaryStockId = null;

                foreach ($allocations as $allocation) {
                    $stock = $allocation['stock'];
                    $qty   = (float) $allocation['qty'];

                    $stockId      = (int) ($stock['id'] ?? 0);
                    $onHandQty    = (float) ($stock['on_hand_qty'] ?? 0);
                    $reservedQty  = (float) ($stock['reserved_qty'] ?? 0);
                    $availableQty = (float) ($stock['available_qty'] ?? 0);
                    $unitCost     = (float) ($stock['average_unit_cost'] ?? 0);

                    if ($qty <= 0) {
                        continue;
                    }

                    if ($qty > $availableQty + 0.0005) {
                        throw new \DomainException('Insufficient available quantity during issuance release.');
                    }

                    $newOnHandQty    = $onHandQty - $qty;
                    $newAvailableQty = max(0, $newOnHandQty - $reservedQty);

                    $this->inventoryStocks->update($stockId, [
                        'on_hand_qty'      => $newOnHandQty,
                        'available_qty'    => $newAvailableQty,
                        'last_movement_at' => date('Y-m-d H:i:s'),
                    ]);

                    $this->stockMovements->create([
                        'movement_number'    => $this->generateMovementNumber(),
                        'movement_type'      => 'issuance',
                        'reference_type'     => 'issuance',
                        'reference_id'       => $issuanceId,
                        'item_name'          => $itemName,
                        'inventory_stock_id' => $stockId,
                        'unit'               => $unit,
                        'qty_in'             => 0,
                        'qty_out'            => $qty,
                        'balance_after'      => $newOnHandQty,
                        'unit_cost'          => $unitCost,
                        'performed_by'       => $actorId,
                        'performed_at'       => date('Y-m-d H:i:s'),
                        'remarks'            => 'Issuance release',
                    ]);

                    $issuedQty += $qty;
                    $totalCost += ($qty * $unitCost);

                    if ($primaryStockId === null) {
                        $primaryStockId = $stockId;
                    }
                }

                if ($issuedQty + 0.0005 < $requestedQty) {
                    throw new \DomainException('Issuance release did not allocate full requested quantity.');
                }

                $averageLineCost = $issuedQty > 0 ? $totalCost / $issuedQty : 0;

                $this->issuanceItems->update((int) ($item['id'] ?? 0), [
                    'inventory_stock_id' => $primaryStockId,
                    'issued_qty'         => $issuedQty,
                    'unit_cost'          => round($averageLineCost, 2),
                    'line_total'         => round($totalCost, 2),
                ]);

                $releasedSummary['items_released']++;
                $releasedSummary['total_qty_out'] += $issuedQty;
                $releasedSummary['total_cost'] += $totalCost;
            }

            $this->issuances->update($issuanceId, [
                'status'      => 'released',
                'released_by' => $actorId,
                'released_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $exception) {
            $this->db->transRollback();

            $this->safeAudit(
                actorId: $actorId,
                action: 'issuance.release_failed',
                module: 'issuance',
                referenceType: 'issuance',
                referenceId: $issuanceId,
                oldValues: ['status' => 'approved'],
                newValues: ['error' => $exception->getMessage()],
            );

            throw $exception;
        }

        if (! $this->db->transStatus()) {
            $this->db->transRollback();
            throw new \RuntimeException('Issuance release transaction failed.');
        }

        $this->db->transCommit();

        $this->safeAudit(
            actorId: $actorId,
            action: 'issuance.released',
            module: 'issuance',
            referenceType: 'issuance',
            referenceId: $issuanceId,
            oldValues: ['status' => 'approved'],
            newValues: [
                'status'        => 'released',
                'items_released' => $releasedSummary['items_released'],
                'total_qty_out' => round((float) $releasedSummary['total_qty_out'], 3),
                'total_cost'    => round((float) $releasedSummary['total_cost'], 2),
            ],
        );
    }

    private function generateMovementNumber(): string
    {
        do {
            $number = 'MOVOUT-' . date('Ymd-His') . '-' . random_int(1000, 9999);
        } while ($this->stockMovements->findByNumber($number) !== null);

        return $number;
    }

    /**
     * @param array<string, mixed>|null $oldValues
     * @param array<string, mixed>|null $newValues
     */
    private function safeAudit(
        ?int $actorId,
        string $action,
        string $module,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        try {
            $this->audit->log($actorId, $action, $module, $referenceType, $referenceId, $oldValues, $newValues);
        } catch (\Throwable) {
            // Audit logging should not block the primary workflow.
        }
    }
}
