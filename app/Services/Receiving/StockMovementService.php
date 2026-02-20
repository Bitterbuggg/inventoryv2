<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Receiving\StockMovementRepositoryInterface;

class StockMovementService
{
    public function __construct(private readonly StockMovementRepositoryInterface $stockMovements)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function recordReceivingMovement(array $data): int
    {
        return $this->stockMovements->create([
            'movement_number'   => $this->generateMovementNumber(),
            'movement_type'     => 'receiving',
            'reference_type'    => 'receiving',
            'reference_id'      => (int) ($data['reference_id'] ?? 0),
            'item_name'         => (string) ($data['item_name'] ?? ''),
            'inventory_stock_id' => (int) ($data['inventory_stock_id'] ?? 0),
            'unit'              => (string) ($data['unit'] ?? 'unit'),
            'qty_in'            => (float) ($data['qty_in'] ?? 0),
            'qty_out'           => 0,
            'balance_after'     => (float) ($data['balance_after'] ?? 0),
            'unit_cost'         => (float) ($data['unit_cost'] ?? 0),
            'performed_by'      => (int) ($data['performed_by'] ?? 0),
            'performed_at'      => date('Y-m-d H:i:s'),
            'remarks'           => $data['remarks'] ?? null,
        ]);
    }

    private function generateMovementNumber(): string
    {
        do {
            $number = 'MOV-' . date('Ymd-His') . '-' . random_int(1000, 9999);
        } while ($this->stockMovements->findByNumber($number) !== null);

        return $number;
    }
}
