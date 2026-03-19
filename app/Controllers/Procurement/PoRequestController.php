<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;
use DomainException;
use function auth;

class PoRequestController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $status = trim((string) $this->request->getGet('status'));
        $poRequests = RepositoryServices::poRequestService()->list($status === '' ? null : $status);

        $poRequests = array_map(static function (array $poRequest): array {
            $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);
            if ($purchaseOrderId <= 0) {
                return $poRequest;
            }

            $purchaseOrder = RepositoryServices::purchaseOrderService()->findWithItems($purchaseOrderId);
            if ($purchaseOrder !== null) {
                $poRequest['purchase_order'] = [
                    'po_number'     => $purchaseOrder['po_number'] ?? null,
                    'supplier_name' => $purchaseOrder['supplier_name'] ?? null,
                    'order_date'    => $purchaseOrder['order_date'] ?? null,
                    'total_amount'  => $purchaseOrder['total_amount'] ?? 0,
                    'items'         => $purchaseOrder['items'] ?? [],
                ];
            }

            return $poRequest;
        }, $poRequests);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_request_list_viewed',
            'procurement',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'po_requests_' . date('Ymd_His') . '.csv',
                ['ID', 'PO Request #', 'PO ID', 'Request Date', 'Status', 'Approved By', 'Rejected By'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['po_request_number'] ?? ''),
                    (string) ($row['purchase_order_id'] ?? ''),
                    (string) ($row['request_date'] ?? ''),
                    (string) ($row['status'] ?? ''),
                    (string) ($row['approved_by'] ?? ''),
                    (string) ($row['rejected_by'] ?? ''),
                ], $poRequests),
            );
        }

        return view('procurement/po_requests/index', [
            'poRequests' => $poRequests,
            'status'     => $status,
        ]);
    }

    public function createFromPo(int $poId): RedirectResponse
    {
        try {
            $poRequestId = RepositoryServices::poRequestService()->createFromPurchaseOrder($poId, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_request_created',
            'procurement',
            'po_request',
            $poRequestId,
            ['purchase_order_id' => $poId],
        );

        return redirect()->to('/procurement/po-requests')->with('message', "PO request #{$poRequestId} created.");
    }

    public function approve(int $id): RedirectResponse
    {
        try {
            RepositoryServices::poRequestService()->approve($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_request_approved',
            'procurement',
            'po_request',
            $id,
        );

        return redirect()->to('/procurement/po-requests')->with('message', 'PO request approved.');
    }

    public function reject(int $id): RedirectResponse
    {
        $rules = [
            'reason' => 'required|min_length[3]|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::poRequestService()->reject(
                $id,
                $this->currentUserId(),
                (string) $this->request->getPost('reason'),
            );
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_request_rejected',
            'procurement',
            'po_request',
            $id,
        );

        return redirect()->to('/procurement/po-requests')->with('message', 'PO request rejected.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }
}
