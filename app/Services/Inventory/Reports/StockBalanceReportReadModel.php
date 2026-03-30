<?php

namespace App\Services\Inventory\Reports;

use CodeIgniter\Database\BaseConnection;

class StockBalanceReportReadModel
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $keyword = null): array
    {
        $builder = $this->db->table('inventory_stocks')
            ->select('id, item_name, unit, batch_no, lot_no, expiry_date, on_hand_qty, reserved_qty, available_qty, average_unit_cost, last_movement_at')
            ->orderBy('item_name', 'ASC');

        if ($keyword !== null && trim($keyword) !== '') {
            $builder->like('item_name', trim($keyword));
        }

        return $builder->get()->getResultArray();
    }
}
