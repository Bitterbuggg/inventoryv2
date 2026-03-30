<?php

namespace App\Services\Inventory\Reports;

use CodeIgniter\Database\BaseConnection;

class IssuanceReportReadModel
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
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
}
