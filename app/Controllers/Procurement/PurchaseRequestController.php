<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use DomainException;
use InvalidArgumentException;
use function auth;

class PurchaseRequestController extends BaseController
{
    public function index(): string
    {
        $status = trim((string) $this->request->getGet('status'));

        return view('procurement/purchase_requests/index', [
            'requests' => RepositoryServices::purchaseRequestService()->list($status === '' ? null : $status),
            'status'   => $status,
        ]);
    }

    public function create(): string
    {
        return view('procurement/purchase_requests/create');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'request_date' => 'required|valid_date[Y-m-d]',
            'needed_date'  => 'permit_empty|valid_date[Y-m-d]',
            'remarks'      => 'permit_empty|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $purchaseRequestId = RepositoryServices::purchaseRequestService()->create([
                'requested_by' => $this->currentUserId(),
                'request_date' => (string) $this->request->getPost('request_date'),
                'needed_date'  => $this->request->getPost('needed_date'),
                'remarks'      => $this->request->getPost('remarks'),
                'items'        => $this->extractItemsFromPost(),
            ]);
        } catch (DomainException|InvalidArgumentException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()
            ->to('/procurement/purchase-requests')
            ->with('message', "Purchase request #{$purchaseRequestId} created.");
    }

    public function submit(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseRequestService()->submit($id);
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/procurement/purchase-requests')->with('message', 'Purchase request submitted for approval.');
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseRequestService()->cancel($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('/procurement/purchase-requests')->with('message', 'Purchase request cancelled.');
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
        $itemNames          = (array) $this->request->getPost('item_name');
        $requestedQuantites = (array) $this->request->getPost('requested_qty');
        $units              = (array) $this->request->getPost('unit');
        $estimatedUnitCosts = (array) $this->request->getPost('estimated_unit_cost');
        $notes              = (array) $this->request->getPost('notes');

        $items = [];

        foreach ($itemNames as $index => $itemName) {
            $items[] = [
                'item_name'           => (string) $itemName,
                'requested_qty'       => $requestedQuantites[$index] ?? null,
                'unit'                => $units[$index] ?? 'unit',
                'estimated_unit_cost' => $estimatedUnitCosts[$index] ?? null,
                'notes'               => $notes[$index] ?? null,
            ];
        }

        return $items;
    }
}
