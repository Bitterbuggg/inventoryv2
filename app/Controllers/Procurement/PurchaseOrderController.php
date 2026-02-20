<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use DomainException;
use function auth;

class PurchaseOrderController extends BaseController
{
    public function index(): string
    {
        $status = trim((string) $this->request->getGet('status'));

        return view('procurement/purchase_orders/index', [
            'purchaseOrders' => RepositoryServices::purchaseOrderService()->list($status === '' ? null : $status),
            'status'         => $status,
        ]);
    }

    public function createFromPr(int $prId): RedirectResponse
    {
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

        return redirect()->to('/procurement/purchase-orders')->with('message', "Purchase order #{$purchaseOrderId} created.");
    }

    public function issue(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseOrderService()->issue($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/procurement/purchase-orders')->with('message', 'Purchase order issued.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }
}
