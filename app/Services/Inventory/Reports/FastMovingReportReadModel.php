<?php

namespace App\Services\Inventory\Reports;

use CodeIgniter\Database\BaseConnection;

class FastMovingReportReadModel
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $dateFrom = null, ?string $dateTo = null, int $limit = 20): array
    {
        $builder = $this->db->table('stock_movements')
            ->select('item_name, unit, SUM(qty_out) AS total_qty_out')
            ->where('movement_type', 'issuance')
            ->groupBy('item_name, unit')
            ->orderBy('total_qty_out', 'DESC')
            ->limit(max(1, $limit));

        if ($dateFrom !== null && trim($dateFrom) !== '') {
            $builder->where('performed_at >=', trim($dateFrom) . ' 00:00:00');
        }

        if ($dateTo !== null && trim($dateTo) !== '') {
            $builder->where('performed_at <=', trim($dateTo) . ' 23:59:59');
        }

        return $builder->get()->getResultArray();
    }
}
