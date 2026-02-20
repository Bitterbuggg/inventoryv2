<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use DomainException;
use function auth;

class PurchaseApprovalController extends BaseController
{
    public function pending(): string
    {
        return view('procurement/approvals/pending', [
            'approvals' => RepositoryServices::approvalService()->listPending(),
        ]);
    }

    public function approve(int $id): RedirectResponse
    {
        $rules = [
            'comments' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::approvalService()->approve(
                $id,
                $this->currentUserId(),
                $this->request->getPost('comments') !== null ? (string) $this->request->getPost('comments') : null,
            );
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/procurement/approvals/pending')->with('message', 'Approval completed.');
    }

    public function reject(int $id): RedirectResponse
    {
        $rules = [
            'comments' => 'required|min_length[3]|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::approvalService()->reject(
                $id,
                $this->currentUserId(),
                (string) $this->request->getPost('comments'),
            );
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/procurement/approvals/pending')->with('message', 'Approval rejected.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }
}
