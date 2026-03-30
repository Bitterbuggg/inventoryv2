<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class SampleWorkflowSeeder extends Seeder
{
    private const SEED_SOURCE = 'workflow_demo';

    public function run(): void
    {
        $this->call(AuthRbacSeeder::class);
        $this->call(SampleCatalogSeeder::class);

        $users = $this->resolveUsers();
        $products = $this->loadActiveProducts(12);
        $suppliers = $this->loadActiveSuppliers(3);

        if (count($products) < 10) {
            throw new \RuntimeException('SampleWorkflowSeeder requires at least 10 active products.');
        }

        if (count($suppliers) < 2) {
            throw new \RuntimeException('SampleWorkflowSeeder requires at least 2 active suppliers.');
        }

        $timeline = $this->buildTimeline();

        $this->db->transBegin();

        try {
            $this->cleanupDemoData();
            $workflow = $this->seedWorkflow($users, $products, $suppliers, $timeline);
            $this->seedAuditLogs($workflow, $users, $timeline);
            $this->seedAnalytics($workflow, $users, $timeline);
        } catch (\Throwable $exception) {
            $this->db->transRollback();
            throw $exception;
        }

        if (! $this->db->transStatus()) {
            $this->db->transRollback();
            throw new \RuntimeException('Sample workflow seed transaction failed.');
        }

        $this->db->transCommit();
    }

    /**
     * @param array<string, int> $users
     * @param array<int, array<string, mixed>> $products
     * @param array<int, array<string, mixed>> $suppliers
     * @param array<string, string> $timeline
     *
     * @return array<string, mixed>
     */
    private function seedWorkflow(array $users, array $products, array $suppliers, array $timeline): array
    {
        $employeeId = $users['employee'];
        $adminId = $users['admin'];
        $itStaffId = $users['it_staff'];

        $requests = [];
        $purchaseOrders = [];
        $poRequests = [];
        $receivings = [];
        $issuances = [];

        $requests['draft'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0001',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['pr_draft_created']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['pr_draft_created'], '+7 days')),
            'remarks' => 'Seeded workflow demo draft request for training.',
            'status' => 'draft',
            'created_at' => $timeline['pr_draft_created'],
            'updated_at' => $timeline['pr_draft_created'],
        ], [
            $this->purchaseRequestItemData($products[0], 12, null, 14.50, 'Clinic replenishment buffer.'),
            $this->purchaseRequestItemData($products[1], 8, null, 9.25, 'Secondary stock line.'),
        ]);

        $requests['submitted'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0002',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['pr_submitted']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['pr_submitted'], '+5 days')),
            'remarks' => 'Seeded workflow demo request awaiting approval.',
            'status' => 'submitted',
            'submitted_at' => $timeline['pr_submitted'],
            'created_at' => $this->shiftTime($timeline['pr_submitted'], '-45 minutes'),
            'updated_at' => $timeline['pr_submitted'],
        ], [
            $this->purchaseRequestItemData($products[2], 16, null, 18.25, 'Ward restock request.'),
            $this->purchaseRequestItemData($products[3], 6, null, 42.00, 'High-priority line item.'),
        ]);

        $this->insert('approvals', [
            'reference_type' => 'purchase_request',
            'reference_id' => $requests['submitted']['id'],
            'approval_level' => 1,
            'approver_id' => null,
            'decision' => 'pending',
            'decision_at' => null,
            'comments' => 'Pending seeded approval queue.',
            'created_at' => $timeline['pr_submitted'],
            'updated_at' => $timeline['pr_submitted'],
        ]);

        $requests['approved'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0003',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['pr_approved']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['pr_approved'], '+3 days')),
            'remarks' => 'Seeded workflow demo request approved and ready for PO conversion.',
            'status' => 'approved',
            'submitted_at' => $this->shiftTime($timeline['pr_approved'], '-40 minutes'),
            'approved_by' => $adminId,
            'approved_at' => $timeline['pr_approved'],
            'created_at' => $this->shiftTime($timeline['pr_approved'], '-2 hours'),
            'updated_at' => $timeline['pr_approved'],
        ], [
            $this->purchaseRequestItemData($products[4], 24, 24, 21.50, 'Approved catalog request sample.'),
        ]);

        $requests['rejected'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0004',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['pr_rejected']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['pr_rejected'], '+4 days')),
            'remarks' => 'Seeded workflow demo request rejected for reference.',
            'status' => 'rejected',
            'submitted_at' => $this->shiftTime($timeline['pr_rejected'], '-55 minutes'),
            'rejected_by' => $adminId,
            'rejected_at' => $timeline['pr_rejected'],
            'rejection_reason' => 'Budget hold for seeded demo request.',
            'created_at' => $this->shiftTime($timeline['pr_rejected'], '-3 hours'),
            'updated_at' => $timeline['pr_rejected'],
        ], [
            $this->purchaseRequestItemData($products[5], 5, 0, 77.80, 'Rejected sample line.'),
        ]);

        $requests['po_draft'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0005',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['po_draft_created']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['po_draft_created'], '+6 days')),
            'remarks' => 'Seeded demo request already converted into a draft PO.',
            'status' => 'converted_to_po',
            'submitted_at' => $this->shiftTime($timeline['po_draft_created'], '-2 hours'),
            'approved_by' => $adminId,
            'approved_at' => $this->shiftTime($timeline['po_draft_created'], '-90 minutes'),
            'created_at' => $this->shiftTime($timeline['po_draft_created'], '-4 hours'),
            'updated_at' => $timeline['po_draft_created'],
        ], [
            $this->purchaseRequestItemData($products[6], 10, 10, 31.20, 'Draft PO conversion sample.'),
        ]);

        $purchaseOrders['draft'] = $this->createPurchaseOrder([
            'po_number' => 'PO-DEMO-0001',
            'purchase_request_id' => $requests['po_draft']['id'],
            'supplier_id' => (int) $suppliers[0]['id'],
            'supplier_name' => (string) $suppliers[0]['supplier_name'],
            'order_date' => $this->dateOnly($timeline['po_draft_created']),
            'status' => 'draft',
            'issued_by' => null,
            'issued_at' => null,
            'created_at' => $timeline['po_draft_created'],
            'updated_at' => $timeline['po_draft_created'],
        ], [
            $this->purchaseOrderItemData($requests['po_draft']['items'][0], 10, 0, 31.20),
        ]);

        $requests['po_pending'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0006',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['po_pending_issued']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['po_pending_issued'], '+5 days')),
            'remarks' => 'Seeded demo request converted to an issued PO with pending PO request.',
            'status' => 'converted_to_po',
            'submitted_at' => $this->shiftTime($timeline['po_pending_issued'], '-3 hours'),
            'approved_by' => $adminId,
            'approved_at' => $this->shiftTime($timeline['po_pending_issued'], '-2 hours'),
            'created_at' => $this->shiftTime($timeline['po_pending_issued'], '-5 hours'),
            'updated_at' => $timeline['po_pending_issued'],
        ], [
            $this->purchaseRequestItemData($products[7], 18, 18, 26.10, 'Issued PO pending PO request sample.'),
        ]);

        $purchaseOrders['pending'] = $this->createPurchaseOrder([
            'po_number' => 'PO-DEMO-0002',
            'purchase_request_id' => $requests['po_pending']['id'],
            'supplier_id' => (int) $suppliers[1]['id'],
            'supplier_name' => (string) $suppliers[1]['supplier_name'],
            'order_date' => $this->dateOnly($timeline['po_pending_issued']),
            'status' => 'issued',
            'issued_by' => $itStaffId,
            'issued_at' => $timeline['po_pending_issued'],
            'created_at' => $this->shiftTime($timeline['po_pending_issued'], '-30 minutes'),
            'updated_at' => $timeline['po_pending_issued'],
        ], [
            $this->purchaseOrderItemData($requests['po_pending']['items'][0], 18, 0, 26.10),
        ]);

        $poRequests['pending'] = $this->createPoRequest([
            'po_request_number' => 'POR-DEMO-0001',
            'purchase_order_id' => $purchaseOrders['pending']['id'],
            'requested_by' => $itStaffId,
            'request_date' => $this->dateOnly($timeline['po_pending_request']),
            'status' => 'pending',
            'created_at' => $timeline['po_pending_request'],
            'updated_at' => $timeline['po_pending_request'],
        ]);

        $requests['po_ready'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0007',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['po_ready_issued']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['po_ready_issued'], '+4 days')),
            'remarks' => 'Seeded demo request with approved PO request ready for receiving conversion.',
            'status' => 'converted_to_po',
            'submitted_at' => $this->shiftTime($timeline['po_ready_issued'], '-3 hours'),
            'approved_by' => $adminId,
            'approved_at' => $this->shiftTime($timeline['po_ready_issued'], '-2 hours'),
            'created_at' => $this->shiftTime($timeline['po_ready_issued'], '-5 hours'),
            'updated_at' => $timeline['po_ready_issued'],
        ], [
            $this->purchaseRequestItemData($products[8], 14, 14, 11.60, 'Ready-for-receiving PO request sample.'),
        ]);

        $purchaseOrders['ready'] = $this->createPurchaseOrder([
            'po_number' => 'PO-DEMO-0003',
            'purchase_request_id' => $requests['po_ready']['id'],
            'supplier_id' => (int) $suppliers[0]['id'],
            'supplier_name' => (string) $suppliers[0]['supplier_name'],
            'order_date' => $this->dateOnly($timeline['po_ready_issued']),
            'status' => 'issued',
            'issued_by' => $itStaffId,
            'issued_at' => $timeline['po_ready_issued'],
            'created_at' => $this->shiftTime($timeline['po_ready_issued'], '-30 minutes'),
            'updated_at' => $timeline['po_ready_issued'],
        ], [
            $this->purchaseOrderItemData($requests['po_ready']['items'][0], 14, 0, 11.60),
        ]);

        $poRequests['approved'] = $this->createPoRequest([
            'po_request_number' => 'POR-DEMO-0002',
            'purchase_order_id' => $purchaseOrders['ready']['id'],
            'requested_by' => $itStaffId,
            'request_date' => $this->dateOnly($timeline['po_ready_request']),
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => $timeline['po_ready_request'],
            'created_at' => $timeline['po_ready_request'],
            'updated_at' => $timeline['po_ready_request'],
        ]);

        $requests['receiving_posted'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0008',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['po_received_issued']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['po_received_issued'], '+3 days')),
            'remarks' => 'Seeded demo request with posted receiving and released stock movement.',
            'status' => 'converted_to_po',
            'submitted_at' => $this->shiftTime($timeline['po_received_issued'], '-4 hours'),
            'approved_by' => $adminId,
            'approved_at' => $this->shiftTime($timeline['po_received_issued'], '-3 hours'),
            'created_at' => $this->shiftTime($timeline['po_received_issued'], '-6 hours'),
            'updated_at' => $timeline['po_received_issued'],
        ], [
            $this->purchaseRequestItemData($products[9], 18, 18, 15.40, 'Posted receiving and issuance sample line.'),
        ]);

        $purchaseOrders['received'] = $this->createPurchaseOrder([
            'po_number' => 'PO-DEMO-0004',
            'purchase_request_id' => $requests['receiving_posted']['id'],
            'supplier_id' => (int) $suppliers[1]['id'],
            'supplier_name' => (string) $suppliers[1]['supplier_name'],
            'order_date' => $this->dateOnly($timeline['po_received_issued']),
            'status' => 'partially_received',
            'issued_by' => $itStaffId,
            'issued_at' => $timeline['po_received_issued'],
            'created_at' => $this->shiftTime($timeline['po_received_issued'], '-45 minutes'),
            'updated_at' => $timeline['receiving_posted'],
        ], [
            $this->purchaseOrderItemData($requests['receiving_posted']['items'][0], 18, 18, 15.40),
        ]);

        $poRequests['converted_posted'] = $this->createPoRequest([
            'po_request_number' => 'POR-DEMO-0003',
            'purchase_order_id' => $purchaseOrders['received']['id'],
            'requested_by' => $itStaffId,
            'request_date' => $this->dateOnly($timeline['po_received_request']),
            'status' => 'converted_to_receiving',
            'approved_by' => $adminId,
            'approved_at' => $timeline['po_received_request'],
            'created_at' => $timeline['po_received_request'],
            'updated_at' => $timeline['receiving_posted'],
        ]);

        $receivings['posted'] = $this->createReceiving([
            'receiving_number' => 'RCV-DEMO-0001',
            'po_request_id' => $poRequests['converted_posted']['id'],
            'purchase_order_id' => $purchaseOrders['received']['id'],
            'supplier_id' => (int) $suppliers[1]['id'],
            'supplier_name' => (string) $suppliers[1]['supplier_name'],
            'received_date' => $this->dateOnly($timeline['receiving_posted']),
            'delivery_reference' => 'DEL-DEMO-0001',
            'received_by' => $itStaffId,
            'verified_by' => $adminId,
            'status' => 'posted',
            'remarks' => 'Seeded posted receiving demo.',
            'posted_at' => $timeline['receiving_posted'],
            'created_at' => $this->shiftTime($timeline['receiving_posted'], '-30 minutes'),
            'updated_at' => $timeline['receiving_posted'],
        ], [
            $this->receivingItemData($purchaseOrders['received']['items'][0], 18, 18, 0, 'DEMO-BATCH-0001', 'DEMO-LOT-0001', $this->dateOnly($this->shiftTime($timeline['receiving_posted'], '+180 days')), 15.40, 'Posted receiving demo line.'),
        ]);

        $stockId = $this->insert('inventory_stocks', [
            'product_id' => $products[9]['id'],
            'item_name' => $products[9]['product_name'],
            'unit' => $products[9]['unit'],
            'batch_no' => 'DEMO-BATCH-0001',
            'lot_no' => 'DEMO-LOT-0001',
            'expiry_date' => $this->dateOnly($this->shiftTime($timeline['receiving_posted'], '+180 days')),
            'on_hand_qty' => 18,
            'reserved_qty' => 0,
            'available_qty' => 18,
            'average_unit_cost' => 15.40,
            'last_movement_at' => $timeline['receiving_posted'],
            'created_at' => $timeline['receiving_posted'],
            'updated_at' => $timeline['receiving_posted'],
        ]);

        $this->insert('stock_movements', [
            'movement_number' => 'MOVSEED-IN-0001',
            'movement_type' => 'receiving',
            'reference_type' => 'receiving',
            'reference_id' => $receivings['posted']['id'],
            'product_id' => $products[9]['id'],
            'item_name' => $products[9]['product_name'],
            'inventory_stock_id' => $stockId,
            'unit' => $products[9]['unit'],
            'qty_in' => 18,
            'qty_out' => 0,
            'balance_after' => 18,
            'unit_cost' => 15.40,
            'performed_by' => $itStaffId,
            'performed_at' => $timeline['receiving_posted'],
            'remarks' => 'Seeded receiving movement demo.',
            'created_at' => $timeline['receiving_posted'],
            'updated_at' => $timeline['receiving_posted'],
        ]);

        $requests['receiving_draft'] = $this->createPurchaseRequest([
            'pr_number' => 'PR-DEMO-0009',
            'requested_by' => $employeeId,
            'request_date' => $this->dateOnly($timeline['po_draft_receiving_issued']),
            'needed_date' => $this->dateOnly($this->shiftTime($timeline['po_draft_receiving_issued'], '+2 days')),
            'remarks' => 'Seeded demo request with an active receiving draft.',
            'status' => 'converted_to_po',
            'submitted_at' => $this->shiftTime($timeline['po_draft_receiving_issued'], '-4 hours'),
            'approved_by' => $adminId,
            'approved_at' => $this->shiftTime($timeline['po_draft_receiving_issued'], '-3 hours'),
            'created_at' => $this->shiftTime($timeline['po_draft_receiving_issued'], '-6 hours'),
            'updated_at' => $timeline['receiving_draft'],
        ], [
            $this->purchaseRequestItemData($products[10], 10, 10, 19.80, 'Receiving draft sample line.'),
        ]);

        $purchaseOrders['receiving_draft'] = $this->createPurchaseOrder([
            'po_number' => 'PO-DEMO-0005',
            'purchase_request_id' => $requests['receiving_draft']['id'],
            'supplier_id' => (int) $suppliers[0]['id'],
            'supplier_name' => (string) $suppliers[0]['supplier_name'],
            'order_date' => $this->dateOnly($timeline['po_draft_receiving_issued']),
            'status' => 'issued',
            'issued_by' => $itStaffId,
            'issued_at' => $timeline['po_draft_receiving_issued'],
            'created_at' => $this->shiftTime($timeline['po_draft_receiving_issued'], '-45 minutes'),
            'updated_at' => $timeline['receiving_draft'],
        ], [
            $this->purchaseOrderItemData($requests['receiving_draft']['items'][0], 10, 0, 19.80),
        ]);

        $poRequests['converted_draft'] = $this->createPoRequest([
            'po_request_number' => 'POR-DEMO-0004',
            'purchase_order_id' => $purchaseOrders['receiving_draft']['id'],
            'requested_by' => $itStaffId,
            'request_date' => $this->dateOnly($timeline['po_draft_receiving_request']),
            'status' => 'converted_to_receiving',
            'approved_by' => $adminId,
            'approved_at' => $timeline['po_draft_receiving_request'],
            'created_at' => $timeline['po_draft_receiving_request'],
            'updated_at' => $timeline['receiving_draft'],
        ]);

        $receivings['draft'] = $this->createReceiving([
            'receiving_number' => 'RCV-DEMO-0002',
            'po_request_id' => $poRequests['converted_draft']['id'],
            'purchase_order_id' => $purchaseOrders['receiving_draft']['id'],
            'supplier_id' => (int) $suppliers[0]['id'],
            'supplier_name' => (string) $suppliers[0]['supplier_name'],
            'received_date' => $this->dateOnly($timeline['receiving_draft']),
            'delivery_reference' => 'DEL-DEMO-0002',
            'received_by' => $itStaffId,
            'verified_by' => null,
            'status' => 'draft',
            'remarks' => 'Seeded draft receiving demo.',
            'created_at' => $timeline['receiving_draft'],
            'updated_at' => $timeline['receiving_draft'],
        ], [
            $this->receivingItemData($purchaseOrders['receiving_draft']['items'][0], 10, 8, 2, 'DEMO-BATCH-0002', 'DEMO-LOT-0002', $this->dateOnly($this->shiftTime($timeline['receiving_draft'], '+150 days')), 19.80, 'Draft receiving demo line.'),
        ]);

        $issuances['draft'] = $this->createIssuance([
            'issuance_number' => 'ISS-DEMO-0001',
            'requestor_id' => $employeeId,
            'issue_date' => $this->dateOnly($timeline['issuance_draft']),
            'department' => 'OPD',
            'purpose' => 'Seeded draft issuance reference.',
            'status' => 'draft',
            'remarks' => 'Seeded draft issuance demo.',
            'created_at' => $timeline['issuance_draft'],
            'updated_at' => $timeline['issuance_draft'],
        ], [
            $this->issuanceItemData($products[0], 4, 0, 0, null, 'Draft issuance sample item.'),
        ]);

        $issuances['submitted'] = $this->createIssuance([
            'issuance_number' => 'ISS-DEMO-0002',
            'requestor_id' => $employeeId,
            'issue_date' => $this->dateOnly($timeline['issuance_submitted']),
            'department' => 'Ward A',
            'purpose' => 'Seeded issuance waiting for approval.',
            'status' => 'submitted',
            'submitted_at' => $timeline['issuance_submitted'],
            'remarks' => 'Seeded submitted issuance demo.',
            'created_at' => $this->shiftTime($timeline['issuance_submitted'], '-30 minutes'),
            'updated_at' => $timeline['issuance_submitted'],
        ], [
            $this->issuanceItemData($products[1], 3, 0, 0, null, 'Submitted issuance sample item.'),
        ]);

        $this->insert('approvals', [
            'reference_type' => 'issuance',
            'reference_id' => $issuances['submitted']['id'],
            'approval_level' => 1,
            'approver_id' => null,
            'decision' => 'pending',
            'decision_at' => null,
            'comments' => 'Pending seeded issuance approval.',
            'created_at' => $timeline['issuance_submitted'],
            'updated_at' => $timeline['issuance_submitted'],
        ]);

        $issuances['released'] = $this->createIssuance([
            'issuance_number' => 'ISS-DEMO-0003',
            'requestor_id' => $employeeId,
            'issue_date' => $this->dateOnly($timeline['issuance_released']),
            'department' => 'Emergency Room',
            'purpose' => 'Seeded released issuance linked to posted receiving stock.',
            'status' => 'released',
            'submitted_at' => $this->shiftTime($timeline['issuance_approved'], '-1 hour'),
            'approved_by' => $adminId,
            'approved_at' => $timeline['issuance_approved'],
            'released_by' => $itStaffId,
            'released_at' => $timeline['issuance_released'],
            'remarks' => 'Seeded released issuance demo.',
            'created_at' => $this->shiftTime($timeline['issuance_approved'], '-2 hours'),
            'updated_at' => $timeline['issuance_released'],
        ], [
            $this->issuanceItemData($products[9], 12, 12, 15.40, $stockId, 'Released issuance sample item.'),
        ]);

        $this->insert('approvals', [
            'reference_type' => 'issuance',
            'reference_id' => $issuances['released']['id'],
            'approval_level' => 1,
            'approver_id' => $adminId,
            'decision' => 'approved',
            'decision_at' => $timeline['issuance_approved'],
            'comments' => 'Seeded approved issuance.',
            'created_at' => $this->shiftTime($timeline['issuance_approved'], '-1 hour'),
            'updated_at' => $timeline['issuance_approved'],
        ]);

        $this->insert('issuance_item_allocations', [
            'issuance_id' => $issuances['released']['id'],
            'issuance_item_id' => $issuances['released']['items'][0]['id'],
            'inventory_stock_id' => $stockId,
            'product_id' => $products[9]['id'],
            'item_name' => $products[9]['product_name'],
            'unit' => $products[9]['unit'],
            'batch_no' => 'DEMO-BATCH-0001',
            'lot_no' => 'DEMO-LOT-0001',
            'expiry_date' => $this->dateOnly($this->shiftTime($timeline['receiving_posted'], '+180 days')),
            'qty_issued' => 12,
            'unit_cost' => 15.40,
            'line_total' => 184.80,
            'created_at' => $timeline['issuance_released'],
            'updated_at' => $timeline['issuance_released'],
        ]);

        $this->db->table('inventory_stocks')->where('id', $stockId)->update([
            'on_hand_qty' => 6,
            'reserved_qty' => 0,
            'available_qty' => 6,
            'last_movement_at' => $timeline['issuance_released'],
            'updated_at' => $timeline['issuance_released'],
        ]);

        $this->insert('stock_movements', [
            'movement_number' => 'MOVSEED-OUT-0001',
            'movement_type' => 'issuance',
            'reference_type' => 'issuance',
            'reference_id' => $issuances['released']['id'],
            'product_id' => $products[9]['id'],
            'item_name' => $products[9]['product_name'],
            'inventory_stock_id' => $stockId,
            'unit' => $products[9]['unit'],
            'qty_in' => 0,
            'qty_out' => 12,
            'balance_after' => 6,
            'unit_cost' => 15.40,
            'performed_by' => $itStaffId,
            'performed_at' => $timeline['issuance_released'],
            'remarks' => 'Seeded issuance release demo.',
            'created_at' => $timeline['issuance_released'],
            'updated_at' => $timeline['issuance_released'],
        ]);

        return [
            'purchase_requests' => $requests,
            'purchase_orders' => $purchaseOrders,
            'po_requests' => $poRequests,
            'receivings' => $receivings,
            'issuances' => $issuances,
        ];
    }

    /**
     * @param array<string, mixed> $workflow
     * @param array<string, int> $users
     * @param array<string, string> $timeline
     */
    private function seedAuditLogs(array $workflow, array $users, array $timeline): void
    {
        $rows = [
            [
                'actor_id' => $users['employee'],
                'action' => 'purchase_request.submitted',
                'module' => 'procurement',
                'reference_type' => 'purchase_request',
                'reference_id' => $workflow['purchase_requests']['submitted']['id'],
                'old_values' => json_encode(['status' => 'draft']),
                'new_values' => json_encode(['status' => 'submitted', 'seed_source' => self::SEED_SOURCE]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Demo Agent',
                'created_at' => $timeline['pr_submitted'],
            ],
            [
                'actor_id' => $users['admin'],
                'action' => 'po_request.approved',
                'module' => 'procurement',
                'reference_type' => 'po_request',
                'reference_id' => $workflow['po_requests']['approved']['id'],
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => 'approved', 'seed_source' => self::SEED_SOURCE]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Demo Agent',
                'created_at' => $timeline['po_ready_request'],
            ],
            [
                'actor_id' => $users['it_staff'],
                'action' => 'receiving.posted',
                'module' => 'receiving',
                'reference_type' => 'receiving',
                'reference_id' => $workflow['receivings']['posted']['id'],
                'old_values' => json_encode(['status' => 'draft']),
                'new_values' => json_encode(['status' => 'posted', 'seed_source' => self::SEED_SOURCE]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Demo Agent',
                'created_at' => $timeline['receiving_posted'],
            ],
            [
                'actor_id' => $users['it_staff'],
                'action' => 'issuance.released',
                'module' => 'issuance',
                'reference_type' => 'issuance',
                'reference_id' => $workflow['issuances']['released']['id'],
                'old_values' => json_encode(['status' => 'approved']),
                'new_values' => json_encode(['status' => 'released', 'seed_source' => self::SEED_SOURCE]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Demo Agent',
                'created_at' => $timeline['issuance_released'],
            ],
        ];

        foreach ($rows as $row) {
            $this->insert('audit_logs', $row);
        }
    }

    /**
     * @param array<string, mixed> $workflow
     * @param array<string, int> $users
     * @param array<string, string> $timeline
     */
    private function seedAnalytics(array $workflow, array $users, array $timeline): void
    {
        $events = [
            $this->analyticsEvent('procurement.pr_draft_created', 'procurement', $users['employee'], 'purchase_request', $workflow['purchase_requests']['draft']['id'], '/procurement/purchase-requests/create', 'POST', $timeline['pr_draft_created']),
            $this->analyticsEvent('procurement.pr_submitted', 'procurement', $users['employee'], 'purchase_request', $workflow['purchase_requests']['submitted']['id'], '/procurement/purchase-requests/' . $workflow['purchase_requests']['submitted']['id'] . '/submit', 'POST', $timeline['pr_submitted']),
            $this->analyticsEvent('procurement.pr_approved', 'procurement', $users['admin'], 'purchase_request', $workflow['purchase_requests']['approved']['id'], '/procurement/approvals/' . $workflow['purchase_requests']['approved']['id'] . '/approve', 'POST', $timeline['pr_approved']),
            $this->analyticsEvent('procurement.pr_rejected', 'procurement', $users['admin'], 'purchase_request', $workflow['purchase_requests']['rejected']['id'], '/procurement/approvals/' . $workflow['purchase_requests']['rejected']['id'] . '/reject', 'POST', $timeline['pr_rejected']),
            $this->analyticsEvent('procurement.po_created', 'procurement', $users['it_staff'], 'purchase_order', $workflow['purchase_orders']['draft']['id'], '/procurement/purchase-orders/create-from-pr', 'POST', $timeline['po_draft_created']),
            $this->analyticsEvent('procurement.po_issued', 'procurement', $users['it_staff'], 'purchase_order', $workflow['purchase_orders']['pending']['id'], '/procurement/purchase-orders/' . $workflow['purchase_orders']['pending']['id'] . '/issue', 'POST', $timeline['po_pending_issued']),
            $this->analyticsEvent('procurement.po_request_submitted', 'procurement', $users['it_staff'], 'po_request', $workflow['po_requests']['pending']['id'], '/procurement/po-requests', 'POST', $timeline['po_pending_request']),
            $this->analyticsEvent('procurement.po_request_approved', 'procurement', $users['admin'], 'po_request', $workflow['po_requests']['approved']['id'], '/procurement/po-requests/' . $workflow['po_requests']['approved']['id'] . '/approve', 'POST', $timeline['po_ready_request']),
            $this->analyticsEvent('receiving.posted', 'receiving', $users['it_staff'], 'receiving', $workflow['receivings']['posted']['id'], '/receiving/' . $workflow['receivings']['posted']['id'] . '/post', 'POST', $timeline['receiving_posted']),
            $this->analyticsEvent('receiving.draft_created', 'receiving', $users['it_staff'], 'receiving', $workflow['receivings']['draft']['id'], '/receiving/create-from-po-request/' . $workflow['po_requests']['converted_draft']['id'], 'POST', $timeline['receiving_draft']),
            $this->analyticsEvent('issuance.draft_created', 'inventory', $users['employee'], 'issuance', $workflow['issuances']['draft']['id'], '/inventory/issuance/create', 'POST', $timeline['issuance_draft']),
            $this->analyticsEvent('issuance.submitted', 'inventory', $users['employee'], 'issuance', $workflow['issuances']['submitted']['id'], '/inventory/issuance/' . $workflow['issuances']['submitted']['id'] . '/submit', 'POST', $timeline['issuance_submitted']),
            $this->analyticsEvent('issuance.approved', 'inventory', $users['admin'], 'issuance', $workflow['issuances']['released']['id'], '/inventory/issuance/' . $workflow['issuances']['released']['id'] . '/approve', 'POST', $timeline['issuance_approved']),
            $this->analyticsEvent('issuance.released', 'inventory', $users['it_staff'], 'issuance', $workflow['issuances']['released']['id'], '/inventory/issuance/' . $workflow['issuances']['released']['id'] . '/release', 'POST', $timeline['issuance_released']),
        ];

        foreach ($events as $event) {
            $this->insert('analytics_events', $event);
        }

        $moduleTotals = [];
        $eventTotals = [];

        foreach ($events as $event) {
            $date = substr((string) $event['created_at'], 0, 10);
            $module = (string) ($event['module'] ?? 'unknown');
            $eventName = (string) ($event['event_name'] ?? 'unknown');
            $moduleTotals[$date . '|' . $module] = ($moduleTotals[$date . '|' . $module] ?? 0) + 1;
            $eventTotals[$date . '|' . $module . '|' . $eventName] = ($eventTotals[$date . '|' . $module . '|' . $eventName] ?? 0) + 1;
        }

        foreach ($moduleTotals as $key => $total) {
            [$date, $module] = explode('|', $key, 2);
            $this->insert('analytics_daily_metrics', [
                'metric_date' => $date,
                'metric_key' => 'module.total_events',
                'metric_value' => $total,
                'module' => $module,
                'dimension_json' => json_encode(['seed_source' => self::SEED_SOURCE]),
                'created_at' => $date . ' 23:59:59',
            ]);
        }

        foreach ($eventTotals as $key => $total) {
            [$date, $module, $eventName] = explode('|', $key, 3);
            $this->insert('analytics_daily_metrics', [
                'metric_date' => $date,
                'metric_key' => 'event.count.' . $this->metricKey($eventName),
                'metric_value' => $total,
                'module' => $module,
                'dimension_json' => json_encode(['event_name' => $eventName, 'seed_source' => self::SEED_SOURCE]),
                'created_at' => $date . ' 23:59:59',
            ]);
        }
    }

    private function cleanupDemoData(): void
    {
        $purchaseRequestIds = $this->idsByPrefix('purchase_requests', 'pr_number', 'PR-DEMO-');
        $purchaseOrderIds = $this->idsByPrefix('purchase_orders', 'po_number', 'PO-DEMO-');
        $poRequestIds = $this->idsByPrefix('po_requests', 'po_request_number', 'POR-DEMO-');
        $receivingIds = $this->idsByPrefix('receivings', 'receiving_number', 'RCV-DEMO-');
        $issuanceIds = $this->idsByPrefix('issuances', 'issuance_number', 'ISS-DEMO-');
        $inventoryStockIds = $this->idsByPrefix('inventory_stocks', 'batch_no', 'DEMO-BATCH-');

        $this->db->table('analytics_daily_metrics')->like('dimension_json', self::SEED_SOURCE)->delete();
        $this->db->table('analytics_events')->like('metadata_json', self::SEED_SOURCE)->delete();
        $this->db->table('audit_logs')->groupStart()->like('old_values', self::SEED_SOURCE)->orLike('new_values', self::SEED_SOURCE)->groupEnd()->delete();

        if ($issuanceIds !== []) {
            $this->db->table('issuance_item_allocations')->whereIn('issuance_id', $issuanceIds)->delete();
            $this->db->table('issuance_items')->whereIn('issuance_id', $issuanceIds)->delete();
        }

        $this->db->table('stock_movements')->like('movement_number', 'MOVSEED-', 'after')->delete();

        if ($inventoryStockIds !== []) {
            $this->db->table('inventory_stocks')->whereIn('id', $inventoryStockIds)->delete();
        }

        if ($receivingIds !== []) {
            $this->db->table('receiving_items')->whereIn('receiving_id', $receivingIds)->delete();
            $this->db->table('receivings')->whereIn('id', $receivingIds)->delete();
        }

        if ($poRequestIds !== []) {
            $this->db->table('po_requests')->whereIn('id', $poRequestIds)->delete();
        }

        if ($purchaseOrderIds !== []) {
            $this->db->table('purchase_order_items')->whereIn('purchase_order_id', $purchaseOrderIds)->delete();
            $this->db->table('purchase_orders')->whereIn('id', $purchaseOrderIds)->delete();
        }

        if ($purchaseRequestIds !== []) {
            $this->db->table('approvals')->groupStart()->groupStart()->where('reference_type', 'purchase_request')->whereIn('reference_id', $purchaseRequestIds)->groupEnd()->orGroupStart()->where('reference_type', 'issuance')->whereIn('reference_id', $issuanceIds === [] ? [0] : $issuanceIds)->groupEnd()->groupEnd()->delete();
            $this->db->table('purchase_request_items')->whereIn('purchase_request_id', $purchaseRequestIds)->delete();
            $this->db->table('purchase_requests')->whereIn('id', $purchaseRequestIds)->delete();
        } elseif ($issuanceIds !== []) {
            $this->db->table('approvals')->where('reference_type', 'issuance')->whereIn('reference_id', $issuanceIds)->delete();
        }

        if ($issuanceIds !== []) {
            $this->db->table('issuances')->whereIn('id', $issuanceIds)->delete();
        }
    }

    /**
     * @return array<string, int>
     */
    private function resolveUsers(): array
    {
        /** @var UserModel $users */
        $users = model(UserModel::class);

        return [
            'admin' => $this->resolveUserId($users, 'admin@local.test'),
            'employee' => $this->resolveUserId($users, 'employee@local.test'),
            'it_staff' => $this->resolveUserId($users, 'itstaff@local.test'),
        ];
    }

    private function resolveUserId(UserModel $users, string $email): int
    {
        $user = $users->findByCredentials(['email' => strtolower($email)]);

        if (! $user instanceof User) {
            throw new \RuntimeException('Required seeded user not found for ' . $email . '.');
        }

        return (int) ($user->id ?? 0);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadActiveProducts(int $limit): array
    {
        return $this->db->table('products')
            ->select('id, product_code, product_name, unit')
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadActiveSuppliers(int $limit): array
    {
        return $this->db->table('suppliers')
            ->select('id, supplier_code, supplier_name')
            ->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $items
     *
     * @return array{id:int, items:array<int, array<string, mixed>>}
     */
    private function createPurchaseRequest(array $data, array $items): array
    {
        $purchaseRequestId = $this->insert('purchase_requests', $data);
        $insertedItems = [];

        foreach ($items as $item) {
            $item['purchase_request_id'] = $purchaseRequestId;
            $item['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $item['updated_at'] = $data['updated_at'] ?? $item['created_at'];
            $item['id'] = $this->insert('purchase_request_items', $item);
            $insertedItems[] = $item;
        }

        return ['id' => $purchaseRequestId, 'items' => $insertedItems];
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $items
     *
     * @return array{id:int, items:array<int, array<string, mixed>>}
     */
    private function createPurchaseOrder(array $data, array $items): array
    {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $subtotal += (float) ($item['line_total'] ?? 0);
        }

        $data['subtotal_amount'] = round($subtotal, 2);
        $data['total_amount'] = round($subtotal, 2);

        $purchaseOrderId = $this->insert('purchase_orders', $data);
        $insertedItems = [];

        foreach ($items as $item) {
            $item['purchase_order_id'] = $purchaseOrderId;
            $item['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $item['updated_at'] = $data['updated_at'] ?? $item['created_at'];
            $item['id'] = $this->insert('purchase_order_items', $item);
            $insertedItems[] = $item;
        }

        return ['id' => $purchaseOrderId, 'items' => $insertedItems];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array{id:int}
     */
    private function createPoRequest(array $data): array
    {
        return ['id' => $this->insert('po_requests', $data)];
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $items
     *
     * @return array{id:int, items:array<int, array<string, mixed>>}
     */
    private function createReceiving(array $data, array $items): array
    {
        $receivingId = $this->insert('receivings', $data);
        $insertedItems = [];

        foreach ($items as $item) {
            $item['receiving_id'] = $receivingId;
            $item['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $item['updated_at'] = $data['updated_at'] ?? $item['created_at'];
            $item['id'] = $this->insert('receiving_items', $item);
            $insertedItems[] = $item;
        }

        return ['id' => $receivingId, 'items' => $insertedItems];
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $items
     *
     * @return array{id:int, items:array<int, array<string, mixed>>}
     */
    private function createIssuance(array $data, array $items): array
    {
        $issuanceId = $this->insert('issuances', $data);
        $insertedItems = [];

        foreach ($items as $item) {
            $item['issuance_id'] = $issuanceId;
            $item['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $item['updated_at'] = $data['updated_at'] ?? $item['created_at'];
            $item['id'] = $this->insert('issuance_items', $item);
            $insertedItems[] = $item;
        }

        return ['id' => $issuanceId, 'items' => $insertedItems];
    }

    /**
     * @param array<string, mixed> $product
     *
     * @return array<string, mixed>
     */
    private function purchaseRequestItemData(array $product, float $requestedQty, ?float $approvedQty, float $estimatedUnitCost, ?string $notes = null): array
    {
        return [
            'product_id' => (int) ($product['id'] ?? 0),
            'item_name' => (string) ($product['product_name'] ?? ''),
            'requested_qty' => $requestedQty,
            'approved_qty' => $approvedQty,
            'unit' => (string) ($product['unit'] ?? 'unit'),
            'estimated_unit_cost' => $estimatedUnitCost,
            'notes' => $notes,
        ];
    }

    /**
     * @param array<string, mixed> $purchaseRequestItem
     *
     * @return array<string, mixed>
     */
    private function purchaseOrderItemData(array $purchaseRequestItem, float $orderedQty, float $receivedQty, float $unitCost): array
    {
        return [
            'purchase_request_item_id' => (int) ($purchaseRequestItem['id'] ?? 0),
            'product_id' => (int) ($purchaseRequestItem['product_id'] ?? 0),
            'item_name' => (string) ($purchaseRequestItem['item_name'] ?? ''),
            'unit' => (string) ($purchaseRequestItem['unit'] ?? 'unit'),
            'ordered_qty' => $orderedQty,
            'received_qty' => $receivedQty,
            'unit_cost' => $unitCost,
            'line_total' => round($orderedQty * $unitCost, 2),
        ];
    }

    /**
     * @param array<string, mixed> $purchaseOrderItem
     *
     * @return array<string, mixed>
     */
    private function receivingItemData(array $purchaseOrderItem, float $receivedQty, float $acceptedQty, float $rejectedQty, string $batchNo, string $lotNo, string $expiryDate, float $unitCost, ?string $remarks = null): array
    {
        return [
            'purchase_order_item_id' => (int) ($purchaseOrderItem['id'] ?? 0),
            'product_id' => (int) ($purchaseOrderItem['product_id'] ?? 0),
            'item_name' => (string) ($purchaseOrderItem['item_name'] ?? ''),
            'unit' => (string) ($purchaseOrderItem['unit'] ?? 'unit'),
            'received_qty' => $receivedQty,
            'accepted_qty' => $acceptedQty,
            'rejected_qty' => $rejectedQty,
            'batch_no' => $batchNo,
            'lot_no' => $lotNo,
            'expiry_date' => $expiryDate,
            'unit_cost' => $unitCost,
            'line_total' => round($acceptedQty * $unitCost, 2),
            'remarks' => $remarks,
        ];
    }

    /**
     * @param array<string, mixed> $product
     *
     * @return array<string, mixed>
     */
    private function issuanceItemData(array $product, float $requestedQty, float $issuedQty, float $unitCost, ?int $inventoryStockId, ?string $remarks = null): array
    {
        return [
            'product_id' => (int) ($product['id'] ?? 0),
            'item_name' => (string) ($product['product_name'] ?? ''),
            'unit' => (string) ($product['unit'] ?? 'unit'),
            'inventory_stock_id' => $inventoryStockId,
            'requested_qty' => $requestedQty,
            'issued_qty' => $issuedQty,
            'unit_cost' => $unitCost,
            'line_total' => round($issuedQty * $unitCost, 2),
            'remarks' => $remarks,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function buildTimeline(): array
    {
        return [
            'pr_draft_created' => $this->timestamp('-7 days 09:15:00'),
            'pr_submitted' => $this->timestamp('-6 days 10:30:00'),
            'pr_approved' => $this->timestamp('-5 days 09:40:00'),
            'pr_rejected' => $this->timestamp('-5 days 14:10:00'),
            'po_draft_created' => $this->timestamp('-4 days 09:20:00'),
            'po_pending_issued' => $this->timestamp('-3 days 09:30:00'),
            'po_pending_request' => $this->timestamp('-3 days 10:00:00'),
            'po_ready_issued' => $this->timestamp('-3 days 13:15:00'),
            'po_ready_request' => $this->timestamp('-3 days 14:00:00'),
            'po_received_issued' => $this->timestamp('-2 days 08:30:00'),
            'po_received_request' => $this->timestamp('-2 days 09:00:00'),
            'receiving_posted' => $this->timestamp('-2 days 11:20:00'),
            'po_draft_receiving_issued' => $this->timestamp('-1 day 08:15:00'),
            'po_draft_receiving_request' => $this->timestamp('-1 day 09:00:00'),
            'receiving_draft' => $this->timestamp('-1 day 10:10:00'),
            'issuance_draft' => $this->timestamp('-1 day 13:20:00'),
            'issuance_submitted' => $this->timestamp('-1 day 15:10:00'),
            'issuance_approved' => $this->timestamp('today 08:20:00'),
            'issuance_released' => $this->timestamp('today 10:00:00'),
        ];
    }

    private function timestamp(string $expression): string
    {
        return date('Y-m-d H:i:s', strtotime($expression));
    }

    private function shiftTime(string $timestamp, string $modifier): string
    {
        return date('Y-m-d H:i:s', strtotime($timestamp . ' ' . $modifier));
    }

    private function dateOnly(string $timestamp): string
    {
        return substr($timestamp, 0, 10);
    }

    private function metricKey(string $raw): string
    {
        $normalized = strtolower(trim($raw));
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized) ?? 'unknown';
        $normalized = trim($normalized, '_');

        return $normalized === '' ? 'unknown' : $normalized;
    }

    /**
     * @return array<string, mixed>
     */
    private function analyticsEvent(string $eventName, string $module, int $actorId, string $referenceType, int $referenceId, string $route, string $method, string $createdAt): array
    {
        return [
            'event_name' => $eventName,
            'module' => $module,
            'actor_id' => $actorId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'route' => $route,
            'method' => $method,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder Demo Agent',
            'metadata_json' => json_encode(['seed_source' => self::SEED_SOURCE, 'seeded' => true]),
            'created_at' => $createdAt,
        ];
    }

    /**
     * @return array<int, int>
     */
    private function idsByPrefix(string $table, string $column, string $prefix): array
    {
        $rows = $this->db->table($table)
            ->select('id')
            ->like($column, $prefix, 'after')
            ->get()
            ->getResultArray();

        return array_map(static fn (array $row): int => (int) ($row['id'] ?? 0), $rows);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function insert(string $table, array $data): int
    {
        $this->db->table($table)->insert($data);

        return (int) $this->db->insertID();
    }
}
