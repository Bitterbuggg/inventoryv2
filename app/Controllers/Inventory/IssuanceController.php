<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use function auth;

class IssuanceController extends BaseController
{
    public function index(): string
    {
        $status = trim((string) $this->request->getGet('status'));

        return view('inventory/issuance/index', [
            'issuances' => RepositoryServices::issuanceService()->list($status === '' ? null : $status),
            'status'    => $status,
        ]);
    }

    public function create(): string
    {
        return view('inventory/issuance/create');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'issue_date'  => 'required|valid_date[Y-m-d]',
            'department'  => 'permit_empty|max_length[120]',
            'purpose'     => 'permit_empty|max_length[5000]',
            'remarks'     => 'permit_empty|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $issuanceId = RepositoryServices::issuanceService()->createDraft([
                'requestor_id' => $this->currentUserId(),
                'issue_date'   => (string) $this->request->getPost('issue_date'),
                'department'   => $this->request->getPost('department'),
                'purpose'      => $this->request->getPost('purpose'),
                'remarks'      => $this->request->getPost('remarks'),
                'items'        => $this->extractItemsFromPost(),
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $issuanceId)->with('message', 'Issuance draft created.');
    }

    public function show(int $id): string|RedirectResponse
    {
        $issuance = RepositoryServices::issuanceService()->findWithItems($id);

        if ($issuance === null) {
            return redirect()->to('/inventory/issuance')->with('error', 'Issuance record not found.');
        }

        return view('inventory/issuance/show', [
            'issuance' => $issuance,
        ]);
    }

    public function submit(int $id): RedirectResponse
    {
        try {
            RepositoryServices::issuanceService()->submit($id);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance submitted for approval.');
    }

    public function release(int $id): RedirectResponse
    {
        try {
            RepositoryServices::issuanceReleaseService()->release($id, $this->currentUserId());
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance released and stock updated.');
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            RepositoryServices::issuanceService()->cancel(
                $id,
                $this->currentUserId(),
                $this->request->getPost('reason') !== null ? (string) $this->request->getPost('reason') : null,
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance cancelled.');
    }

    private function currentUserId(): int
    {
        $user = auth()->user();

        return (int) ($user->id ?? 0);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extractItemsFromPost(): array
    {
        $itemNames     = (array) $this->request->getPost('item_name');
        $units         = (array) $this->request->getPost('unit');
        $requestedQtys = (array) $this->request->getPost('requested_qty');
        $remarks       = (array) $this->request->getPost('item_remarks');

        $items = [];

        foreach ($itemNames as $index => $itemName) {
            $items[] = [
                'item_name'     => $itemName,
                'unit'          => $units[$index] ?? 'unit',
                'requested_qty' => $requestedQtys[$index] ?? 0,
                'remarks'       => $remarks[$index] ?? null,
            ];
        }

        return $items;
    }
}
