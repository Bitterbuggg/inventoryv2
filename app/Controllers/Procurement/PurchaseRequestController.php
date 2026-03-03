<?php

namespace App\Controllers\Procurement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;
use DomainException;
use InvalidArgumentException;
use function auth;

class PurchaseRequestController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $status = trim((string) $this->request->getGet('status'));
        $requests = RepositoryServices::purchaseRequestService()->list($status === '' ? null : $status);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_list_viewed',
            'procurement',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'purchase_requests_' . date('Ymd_His') . '.csv',
                ['ID', 'PR Number', 'Requested By', 'Request Date', 'Status', 'Remarks'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['pr_number'] ?? ''),
                    (string) ($row['requested_by'] ?? ''),
                    (string) ($row['request_date'] ?? ''),
                    (string) ($row['status'] ?? ''),
                    (string) ($row['remarks'] ?? ''),
                ], $requests),
            );
        }

        return view('procurement/purchase_requests/index', [
            'requests' => $requests,
            'status'   => $status,
        ]);
    }

public function create()
    {
        // Connect to your database
        $db = \Config\Database::connect();
        
        // Fetch unique item names currently in your inventory to populate the dropdown
        $query = $db->table('inventory_stocks')->select('item_name')->distinct()->orderBy('item_name', 'ASC')->get();
        $existingItems = $query->getResultArray();
        
        // Convert the database results into a simple array of strings
        $itemsList = array_column($existingItems, 'item_name');

        return view('procurement/purchase_requests/create', [
            'dbItems' => $itemsList
        ]);
    }

    public function edit(int $id): string|RedirectResponse
    {
        $purchaseRequest = RepositoryServices::purchaseRequestService()->findWithItems($id);

        if ($purchaseRequest === null) {
            return redirect()->to('/procurement/purchase-requests')->with('error', 'Purchase request not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'draft') {
            return redirect()->to('/procurement/purchase-requests')->with('error', 'Only draft purchase requests can be edited.');
        }

        return view('procurement/purchase_requests/edit', [
            'purchaseRequest' => $purchaseRequest,
        ]);
    }

    public function itemsCsv(int $id): RedirectResponse|ResponseInterface
    {
        $purchaseRequest = RepositoryServices::purchaseRequestService()->findWithItems($id);

        if ($purchaseRequest === null) {
            return redirect()->to('/procurement/purchase-requests')->with('error', 'Purchase request not found.');
        }

        $rows = $purchaseRequest['items'] ?? [];

        return $this->csvResponse(
            'purchase_request_items_' . ((string) ($purchaseRequest['pr_number'] ?? $id)) . '.csv',
            ['Item Name', 'Requested Qty', 'Unit', 'Estimated Unit Cost', 'Notes'],
            array_map(static fn (array $row): array => [
                (string) ($row['item_name'] ?? ''),
                (string) ($row['requested_qty'] ?? '0'),
                (string) ($row['unit'] ?? ''),
                number_format((float) ($row['estimated_unit_cost'] ?? 0), 2, '.', ''),
                (string) ($row['notes'] ?? ''),
            ], $rows),
        );
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

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_created',
            'procurement',
            'purchase_request',
            $purchaseRequestId,
        );

        return redirect()
            ->to('/procurement/purchase-requests')
            ->with('message', "Purchase request #{$purchaseRequestId} created.");
    }

    public function update(int $id): RedirectResponse
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
            RepositoryServices::purchaseRequestService()->update($id, [
                'request_date' => (string) $this->request->getPost('request_date'),
                'needed_date'  => $this->request->getPost('needed_date'),
                'remarks'      => $this->request->getPost('remarks'),
                'items'        => $this->extractItemsFromPost(),
            ]);
        } catch (DomainException|InvalidArgumentException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_updated',
            'procurement',
            'purchase_request',
            $id,
        );

        return redirect()->to('/procurement/purchase-requests')->with('message', 'Purchase request updated.');
    }

    public function submit(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseRequestService()->submit($id);
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_submitted',
            'procurement',
            'purchase_request',
            $id,
        );

        return redirect()->to('/procurement/purchase-requests')->with('message', 'Purchase request submitted for approval.');
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            RepositoryServices::purchaseRequestService()->cancel($id, $this->currentUserId());
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_cancelled',
            'procurement',
            'purchase_request',
            $id,
        );

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
