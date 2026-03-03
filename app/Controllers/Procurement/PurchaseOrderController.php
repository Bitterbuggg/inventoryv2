<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;
use DomainException;
use function auth;

class PurchaseOrderController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $status = trim((string) $this->request->getGet('status'));
        $purchaseOrders = RepositoryServices::purchaseOrderService()->list($status === '' ? null : $status);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_list_viewed',
            'procurement',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'purchase_orders_' . date('Ymd_His') . '.csv',
                ['ID', 'PO Number', 'PR ID', 'Supplier', 'Order Date', 'Status', 'Total Amount'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['po_number'] ?? ''),
                    (string) ($row['purchase_request_id'] ?? ''),
                    (string) ($row['supplier_name'] ?? ''),
                    (string) ($row['order_date'] ?? ''),
                    (string) ($row['status'] ?? ''),
                    number_format((float) ($row['total_amount'] ?? 0), 2, '.', ''),
                ], $purchaseOrders),
            );
        }

        return view('procurement/purchase_orders/index', [
            'purchaseOrders' => $purchaseOrders,
            'status'         => $status,
        ]);
    }

    public function createFromPr(int $prId): RedirectResponse
    {
        // 1. DATA SAFETY: Check if PO already exists before calling the service
        $existingPOs = RepositoryServices::purchaseOrderService()->list(); 
        $alreadyConverted = array_filter($existingPOs, static fn($po) => (int)$po['purchase_request_id'] === $prId);

        if (!empty($alreadyConverted)) {
            // HCI: Informative clean error message
            return redirect()->back()->with('error', 'A Purchase Order already exists for this request.');
        }

        $rules = [
            'supplier_name' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $purchaseOrderId = RepositoryServices::purchaseOrderService()->createFromPurchaseRequest(
                $prId,
                $this->request->getPost('supplier_name') !== null ? (string) $this->request->getPost('supplier_name') : null,
            );
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_created',
            'procurement',
            'purchase_order',
            $purchaseOrderId,
            ['purchase_request_id' => $prId],
        );

        return redirect()->to('/procurement/purchase-orders')->with('message', "Purchase order #{$purchaseOrderId} created.");
    }

    public function issue(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseOrderService()->issue($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_issued',
            'procurement',
            'purchase_order',
            $id,
        );

        return redirect()->to('/procurement/purchase-orders')->with('message', 'Purchase order issued.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();
        return (int) ($user->id ?? 0);
    }
}