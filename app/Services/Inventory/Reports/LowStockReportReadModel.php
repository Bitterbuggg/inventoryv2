<?php

namespace App\Services\Inventory\Reports;

use CodeIgniter\Database\BaseConnection;

class LowStockReportReadModel
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(float $threshold = 10): array
    {
        return $this->db->table('inventory_stocks')
            ->select('id, item_name, unit, batch_no, lot_no, expiry_date, available_qty, on_hand_qty, reserved_qty')
            ->where('available_qty <=', $threshold)
            ->orderBy('available_qty', 'ASC')
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResultArray();
    }
}
