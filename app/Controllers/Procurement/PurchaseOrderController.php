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
        $purchaseOrders = RepositoryServices::procurementListPresenter()->listPurchaseOrders($status === '' ? null : $status);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_list_viewed',
            'procurement',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::procurementExportPresenter()->purchaseOrdersCsv($purchaseOrders);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('procurement/purchase_orders/index', [
            'purchaseOrders' => $purchaseOrders,
            'status'         => $status,
            'statusOptions'  => RepositoryServices::procurementListPresenter()->purchaseOrderStatusOptions(),
        ]);
    }

    public function createFromPr(int $prId): RedirectResponse
    {
        $user = auth()->user();
        $canCreatePo = $user !== null
            && (
                $user->inGroup('admin')
                || (method_exists($user, 'can') && $user->can('procurement.po.create'))
            );

        if (! $canCreatePo) {
            return redirect()->back()->with('error', 'You do not have permission to create Purchase Orders.');
        }

        $rules = [
            'supplier_id'   => 'permit_empty|integer|greater_than[0]',
            'supplier_name' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $supplierId = (int) ($this->request->getPost('supplier_id') ?? 0);
            $supplierInput = $supplierId > 0
                ? $supplierId
                : ($this->request->getPost('supplier_name') !== null ? (string) $this->request->getPost('supplier_name') : null);

            $purchaseOrderId = RepositoryServices::purchaseOrderService()->createFromPurchaseRequest(
                $prId,
                $supplierInput,
            );
        } catch (DomainException $exception) {
            $message = $exception->getMessage();

            if ($message === 'Purchase order already exists for this purchase request.') {
                $message = 'A Purchase Order already exists for this request.';
            }

            return redirect()->back()->with('error', $message);
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.po_created',
            'procurement',
            'purchase_order',
            $purchaseOrderId,
            ['purchase_request_id' => $prId],
        );

        $redirectPath = ($user !== null && ($user->inGroup('admin') || $user->inGroup('it_staff')))
            ? '/procurement/purchase-orders'
            : '/procurement/purchase-requests';

        return redirect()->to($redirectPath)->with('message', "Purchase order #{$purchaseOrderId} created.");
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
