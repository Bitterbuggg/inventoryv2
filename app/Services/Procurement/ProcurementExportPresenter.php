<?php

namespace App\Services\Procurement;

class ProcurementExportPresenter
{
    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function purchaseRequestsCsv(array $rows): array
    {
        return [
            'filename' => 'purchase_requests_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'PR Number', 'Requested By', 'Request Date', 'Status', 'Remarks'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['pr_number'] ?? ''),
                (string) ($row['requested_by'] ?? ''),
                (string) ($row['request_date'] ?? ''),
                (string) ($row['status'] ?? ''),
                (string) ($row['remarks'] ?? ''),
            ], $rows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function purchaseRequestItemsCsv(string $prNumber, array $rows): array
    {
        return [
            'filename' => 'purchase_request_items_' . $prNumber . '.csv',
            'headers' => ['Item Name', 'Requested Qty', 'Unit', 'Estimated Unit Cost', 'Notes'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['item_name'] ?? ''),
                app_format_quantity($row['requested_qty'] ?? 0, '0', 3, false),
                (string) ($row['unit'] ?? ''),
                number_format((float) ($row['estimated_unit_cost'] ?? 0), 2, '.', ''),
                (string) ($row['notes'] ?? ''),
            ], $rows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function purchaseOrdersCsv(array $rows): array
    {
        return [
            'filename' => 'purchase_orders_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'PO Number', 'PR ID', 'Supplier', 'Order Date', 'Status', 'Total Amount'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['po_number'] ?? ''),
                (string) ($row['purchase_request_id'] ?? ''),
                (string) ($row['supplier_name'] ?? ''),
                (string) ($row['order_date'] ?? ''),
                (string) ($row['status'] ?? ''),
                number_format((float) ($row['total_amount'] ?? 0), 2, '.', ''),
            ], $rows),
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function poRequestsCsv(array $rows): array
    {
        return [
            'filename' => 'po_requests_' . date('Ymd_His') . '.csv',
            'headers' => ['ID', 'PO Request #', 'PO ID', 'Request Date', 'Status', 'Approved By', 'Rejected By'],
            'rows' => array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['po_request_number'] ?? ''),
                (string) ($row['purchase_order_id'] ?? ''),
                (string) ($row['request_date'] ?? ''),
                (string) ($row['status'] ?? ''),
                (string) ($row['approved_by'] ?? ''),
                (string) ($row['rejected_by'] ?? ''),
            ], $rows),
        ];
    }
}
