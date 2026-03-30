<?php

namespace App\Services\Inventory\Reports;

use CodeIgniter\Database\BaseConnection;

class StockMovementReportReadModel
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $dateFrom = null, ?string $dateTo = null, ?string $movementType = null): array
    {
        $builder = $this->db->table('stock_movements')
            ->select('id, movement_number, movement_type, reference_type, reference_id, item_name, unit, qty_in, qty_out, balance_after, unit_cost, performed_by, performed_at')
            ->orderBy('performed_at', 'DESC');

        if ($movementType !== null && trim($movementType) !== '') {
            $builder->where('movement_type', trim($movementType));
        }

        if ($dateFrom !== null && trim($dateFrom) !== '') {
            $builder->where('performed_at >=', trim($dateFrom) . ' 00:00:00');
        }

        if ($dateTo !== null && trim($dateTo) !== '') {
            $builder->where('performed_at <=', trim($dateTo) . ' 23:59:59');
        }

        return $builder->get()->getResultArray();
    }
}
