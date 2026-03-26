<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class ReportingController extends BaseController
{
    public function stockBalance(): string|ResponseInterface
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $rows = RepositoryServices::reportingService()->stockBalance($keyword === '' ? null : $keyword);

        $this->trackReportView('stock_balance', [
            'has_keyword' => $keyword !== '',
        ]);

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'stock_balance_' . date('Ymd_His') . '.csv',
                ['ID', 'Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'On Hand', 'Reserved', 'Available', 'Avg Cost'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['item_name'] ?? ''),
                    (string) ($row['unit'] ?? ''),
                    (string) ($row['batch_no'] ?? ''),
                    (string) ($row['lot_no'] ?? ''),
                    (string) ($row['expiry_date'] ?? ''),
                    (string) ($row['on_hand_qty'] ?? '0'),
                    (string) ($row['reserved_qty'] ?? '0'),
                    (string) ($row['available_qty'] ?? '0'),
                    number_format((float) ($row['average_cost'] ?? 0), 2, '.', ''),
                ], $rows),
            );
        }

        return view('inventory/reports/stock_balance', [
            'rows'    => $rows,
            'keyword' => $keyword,
        ]);
    }

    public function stockMovements(): string|ResponseInterface
    {
        $movementTypeLabels = [
            'receiving'      => 'Receiving',
            'issuance'       => 'Issuance',
            'adjustment_in'  => 'Stock Adjustment In',
            'adjustment_out' => 'Stock Disposal',
            'return'         => 'Return',
        ];

        $referenceTypeLabels = [
            'receiving'         => 'Receiving',
            'issuance'          => 'Issuance',
            'manual_adjustment' => 'Stock Disposal',
        ];

        $dateFrom     = trim((string) $this->request->getGet('date_from'));
        $dateTo       = trim((string) $this->request->getGet('date_to'));
        $movementType = trim((string) $this->request->getGet('movement_type'));
        $rows = RepositoryServices::reportingService()->stockMovements(
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
            $movementType === '' ? null : $movementType,
        );

        $this->trackReportView('stock_movements', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'movement_type' => $movementType,
        ]);

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'stock_movements_' . date('Ymd_His') . '.csv',
                ['ID', 'Movement #', 'Type', 'Reference Type', 'Reference ID', 'Item', 'Unit', 'Qty In', 'Qty Out', 'Balance', 'Performed At'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['movement_number'] ?? ''),
                    $movementTypeLabels[(string) ($row['movement_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($row['movement_type'] ?? ''))),
                    $referenceTypeLabels[(string) ($row['reference_type'] ?? '')] ?? ucwords(str_replace('_', ' ', (string) ($row['reference_type'] ?? ''))),
                    (string) ($row['reference_id'] ?? ''),
                    (string) ($row['item_name'] ?? ''),
                    (string) ($row['unit'] ?? ''),
                    (string) ($row['qty_in'] ?? '0'),
                    (string) ($row['qty_out'] ?? '0'),
                    (string) ($row['balance_after'] ?? '0'),
                    (string) ($row['performed_at'] ?? ''),
                ], $rows),
            );
        }

        return view('inventory/reports/stock_movements', [
            'rows'         => $rows,
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'movement_type' => $movementType,
        ]);
    }

    public function issuances(): string|ResponseInterface
    {
        $status   = trim((string) $this->request->getGet('status'));
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));
        $rows = RepositoryServices::reportingService()->issuances(
            $status === '' ? null : $status,
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
        );

        $this->trackReportView('issuances', [
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'issuance_report_' . date('Ymd_His') . '.csv',
                ['ID', 'Issuance #', 'Requestor ID', 'Issue Date', 'Department', 'Status', 'Total Requested', 'Total Issued'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['issuance_number'] ?? ''),
                    (string) ($row['requestor_id'] ?? ''),
                    (string) ($row['issue_date'] ?? ''),
                    (string) ($row['department'] ?? ''),
                    (string) ($row['status'] ?? ''),
                    (string) ($row['total_requested_qty'] ?? '0'),
                    (string) ($row['total_issued_qty'] ?? '0'),
                ], $rows),
            );
        }

        return view('inventory/reports/issuances', [
            'rows'      => $rows,
            'status'    => $status,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    public function lowStock(): string|ResponseInterface
    {
        $threshold = (int) ($this->request->getGet('threshold') ?? 10);
        $rows = RepositoryServices::reportingService()->lowStock($threshold);

        $this->trackReportView('low_stock', [
            'threshold' => $threshold,
        ]);

        if ($this->shouldExportCsv()) {
            $lowStockRows = array_values(array_filter(
                $rows,
                static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= $threshold,
            ));

            return $this->csvResponse(
                'low_stock_' . date('Ymd_His') . '.csv',
                ['ID', 'Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'Available Qty', 'On Hand', 'Reserved'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['item_name'] ?? ''),
                    (string) ($row['unit'] ?? ''),
                    (string) ($row['batch_no'] ?? ''),
                    (string) ($row['lot_no'] ?? ''),
                    (string) ($row['expiry_date'] ?? ''),
                    (string) ($row['available_qty'] ?? '0'),
                    (string) ($row['on_hand_qty'] ?? '0'),
                    (string) ($row['reserved_qty'] ?? '0'),
                ], $lowStockRows),
            );
        }

        return view('inventory/reports/low_stock', [
            'rows'       => $rows,
            'threshold'  => $threshold,
        ]);
    }

    public function fastMoving(): string|ResponseInterface
    {
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));
        $limit    = (int) ($this->request->getGet('limit') ?? 20);
        $rows = RepositoryServices::reportingService()->fastMoving(
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
            $limit,
        );

        $this->trackReportView('fast_moving', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
        ]);

        if ($this->shouldExportCsv()) {
            $rank = 1;
            $csvRows = [];
            foreach ($rows as $row) {
                $csvRows[] = [
                    (string) $rank,
                    (string) ($row['item_name'] ?? ''),
                    (string) ($row['unit'] ?? ''),
                    number_format((float) ($row['total_qty_out'] ?? 0), 2, '.', ''),
                ];
                $rank++;
            }

            return $this->csvResponse(
                'fast_moving_' . date('Ymd_His') . '.csv',
                ['Rank', 'Item', 'Unit', 'Total Qty Out'],
                $csvRows,
            );
        }

        return view('inventory/reports/fast_moving', [
            'rows'      => $rows,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'limit'     => $limit,
        ]);
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function trackReportView(string $reportName, array $filters): void
    {
        RepositoryServices::analyticsService()->trackCurrentUser(
            'report.viewed',
            'reports',
            'report',
            null,
            [
                'report' => $reportName,
                'filters' => $filters,
            ],
        );
    }
}
