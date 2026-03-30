<?php

namespace App\Services\Inventory\Reports;

class ReportingExportPresenter
{
    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function stockBalanceCsv(array $rows): array
    {
        return [
            'filename' => 'stock_balance_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'On Hand', 'Reserved', 'Available', 'Avg Cost'],
            'rows' => array_map(static fn (array $row): array => [
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
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function stockMovementsCsv(array $rows): array
    {
        return [
            'filename' => 'stock_movements_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'Movement #', 'Type', 'Reference Type', 'Reference ID', 'Item', 'Unit', 'Qty In', 'Qty Out', 'Balance', 'Performed At'],
            'rows' => array_map(fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['movement_number'] ?? ''),
                $this->movementTypeLabel((string) ($row['movement_type'] ?? '')),
                $this->referenceTypeLabel((string) ($row['reference_type'] ?? '')),
                (string) ($row['reference_id'] ?? ''),
                (string) ($row['item_name'] ?? ''),
                (string) ($row['unit'] ?? ''),
                (string) ($row['qty_in'] ?? '0'),
                (string) ($row['qty_out'] ?? '0'),
                (string) ($row['balance_after'] ?? '0'),
                (string) ($row['performed_at'] ?? ''),
            ], $rows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function issuancesCsv(array $rows): array
    {
        return [
            'filename' => 'issuance_report_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'Issuance #', 'Requestor ID', 'Issue Date', 'Department', 'Status', 'Total Requested', 'Total Issued'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['issuance_number'] ?? ''),
                (string) ($row['requestor_id'] ?? ''),
                (string) ($row['issue_date'] ?? ''),
                (string) ($row['department'] ?? ''),
                (string) ($row['status'] ?? ''),
                (string) ($row['total_requested_qty'] ?? '0'),
                (string) ($row['total_issued_qty'] ?? '0'),
            ], $rows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function lowStockCsv(array $rows, float $threshold): array
    {
        $filteredRows = array_values(array_filter(
            $rows,
            static fn (array $row): bool => (float) ($row['available_qty'] ?? 0) <= $threshold,
        ));

        return [
            'filename' => 'low_stock_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'Available Qty', 'On Hand', 'Reserved'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['item_name'] ?? ''),
                (string) ($row['unit'] ?? ''),
                (string) ($row['batch_no'] ?? ''),
                (string) ($row['lot_no'] ?? ''),
                (string) ($row['expiry_date'] ?? ''),
                (string) ($row['available_qty'] ?? '0'),
                (string) ($row['on_hand_qty'] ?? '0'),
                (string) ($row['reserved_qty'] ?? '0'),
            ], $filteredRows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function fastMovingCsv(array $rows): array
    {
        $csvRows = [];
        $rank = 1;

        foreach ($rows as $row) {
            $csvRows[] = [
                (string) $rank,
                (string) ($row['item_name'] ?? ''),
                (string) ($row['unit'] ?? ''),
                number_format((float) ($row['total_qty_out'] ?? 0), 2, '.', ''),
            ];
            $rank++;
        }

        return [
            'filename' => 'fast_moving_' . date('Ymd_His') . '.csv',
            'headers' => ['Rank', 'Item', 'Unit', 'Total Qty Out'],
            'rows' => $csvRows,
        ];
    }

    private function movementTypeLabel(string $movementType): string
    {
        return [
            'receiving' => 'Receiving',
            'issuance' => 'Issuance',
            'adjustment_in' => 'Stock Adjustment In',
            'adjustment_out' => 'Stock Disposal',
            'return' => 'Return',
        ][$movementType] ?? ucwords(str_replace('_', ' ', $movementType));
    }

    private function referenceTypeLabel(string $referenceType): string
    {
        return [
            'receiving' => 'Receiving',
            'issuance' => 'Issuance',
            'manual_adjustment' => 'Stock Disposal',
        ][$referenceType] ?? ucwords(str_replace('_', ' ', $referenceType));
    }
}
