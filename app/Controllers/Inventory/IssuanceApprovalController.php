<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use function auth;

class IssuanceApprovalController extends BaseController
{
    public function approve(int $id): RedirectResponse
    {
        $rules = [
            'comments' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::issuanceApprovalService()->approve(
                $id,
                $this->currentUserId(),
                $this->request->getPost('comments') !== null ? (string) $this->request->getPost('comments') : null,
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance approved.');
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
            RepositoryServices::issuanceApprovalService()->reject(
                $id,
                $this->currentUserId(),
                (string) $this->request->getPost('reason'),
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance rejected.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }
}
