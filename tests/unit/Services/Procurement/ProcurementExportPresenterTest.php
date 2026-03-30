<?php

use App\Services\Procurement\ProcurementExportPresenter;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProcurementExportPresenterTest extends CIUnitTestCase
{
    public function testPurchaseRequestsCsvBuildsExpectedColumns(): void
    {
        $presenter = new ProcurementExportPresenter();

        $csv = $presenter->purchaseRequestsCsv([
            [
                'id' => 7,
                'pr_number' => 'PR-001',
                'requested_by' => 'Employee',
                'request_date' => '2026-03-30',
                'status' => 'submitted',
                'remarks' => 'Urgent',
            ],
        ]);

        $this->assertSame(['ID', 'PR Number', 'Requested By', 'Request Date', 'Status', 'Remarks'], $csv['headers']);
        $this->assertSame('PR-001', $csv['rows'][0][1]);
        $this->assertSame('submitted', $csv['rows'][0][4]);
    }

    public function testPurchaseRequestItemsCsvFormatsUnitCost(): void
    {
        $presenter = new ProcurementExportPresenter();

        $csv = $presenter->purchaseRequestItemsCsv('PR-001', [
            [
                'item_name' => 'Bandage',
                'requested_qty' => 2,
                'unit' => 'pack',
                'estimated_unit_cost' => 12.5,
                'notes' => 'Sterile',
            ],
        ]);

        $this->assertSame('purchase_request_items_PR-001.csv', $csv['filename']);
        $this->assertSame('12.50', $csv['rows'][0][3]);
    }

    public function testPurchaseOrdersCsvFormatsTotalAmount(): void
    {
        $presenter = new ProcurementExportPresenter();

        $csv = $presenter->purchaseOrdersCsv([
            [
                'id' => 3,
                'po_number' => 'PO-009',
                'purchase_request_id' => 8,
                'supplier_name' => 'Supplier A',
                'order_date' => '2026-03-30',
                'status' => 'issued',
                'total_amount' => 1500,
            ],
        ]);

        $this->assertSame('PO-009', $csv['rows'][0][1]);
        $this->assertSame('1500.00', $csv['rows'][0][6]);
    }
}
