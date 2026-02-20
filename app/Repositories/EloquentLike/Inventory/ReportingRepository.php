<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Repositories\Contracts\Inventory\ReportingRepositoryInterface;
use CodeIgniter\Database\BaseConnection;

class ReportingRepository implements ReportingRepositoryInterface
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    public function stockBalanceReport(?string $keyword = null): array
    {
        $builder = $this->db->table('inventory_stocks')
            ->select('id, item_name, unit, batch_no, lot_no, expiry_date, on_hand_qty, reserved_qty, available_qty, average_unit_cost, last_movement_at')
            ->orderBy('item_name', 'ASC');

        if ($keyword !== null && trim($keyword) !== '') {
            $builder->like('item_name', trim($keyword));
        }

        return $builder->get()->getResultArray();
    }

    public function stockMovementReport(?string $dateFrom = null, ?string $dateTo = null, ?string $movementType = null): array
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

    public function issuanceReport(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('issuances i')
            ->select('i.id, i.issuance_number, i.requestor_id, i.issue_date, i.department, i.status, i.approved_by, i.released_by, COALESCE(SUM(ii.requested_qty),0) AS total_requested_qty, COALESCE(SUM(ii.issued_qty),0) AS total_issued_qty')
            ->join('issuance_items ii', 'ii.issuance_id = i.id', 'left')
            ->groupBy('i.id')
            ->orderBy('i.id', 'DESC');

        if ($status !== null && trim($status) !== '') {
            $builder->where('i.status', trim($status));
        }

        if ($dateFrom !== null && trim($dateFrom) !== '') {
            $builder->where('i.issue_date >=', trim($dateFrom));
        }

        if ($dateTo !== null && trim($dateTo) !== '') {
            $builder->where('i.issue_date <=', trim($dateTo));
        }

        return $builder->get()->getResultArray();
    }

    public function lowStockReport(float $threshold = 10): array
    {
        return $this->db->table('inventory_stocks')
            ->select('id, item_name, unit, batch_no, lot_no, expiry_date, available_qty, on_hand_qty, reserved_qty')
            ->where('available_qty <=', $threshold)
            ->orderBy('available_qty', 'ASC')
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function fastMovingReport(?string $dateFrom = null, ?string $dateTo = null, int $limit = 20): array
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
