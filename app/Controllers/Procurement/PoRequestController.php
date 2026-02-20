<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use DomainException;
use function auth;

class PoRequestController extends BaseController
{
    public function index(): string
    {
        $status = trim((string) $this->request->getGet('status'));

        return view('procurement/po_requests/index', [
            'poRequests' => RepositoryServices::poRequestService()->list($status === '' ? null : $status),
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

        return redirect()->to('/procurement/po-requests')->with('message', "PO request #{$poRequestId} created.");
    }

    public function approve(int $id): RedirectResponse
    {
        try {
            RepositoryServices::poRequestService()->approve($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

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

        return redirect()->to('/procurement/po-requests')->with('message', 'PO request rejected.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }
}
