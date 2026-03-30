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
        $requests = RepositoryServices::procurementListPresenter()->listPurchaseRequests($status === '' ? null : $status);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_list_viewed',
            'procurement',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::procurementExportPresenter()->purchaseRequestsCsv($requests);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('procurement/purchase_requests/index', [
            'requests'      => $requests,
            'status'        => $status,
            'statusOptions' => RepositoryServices::procurementListPresenter()->purchaseRequestStatusOptions(),
            'suppliers'     => RepositoryServices::purchaseOrderService()->listActiveSuppliers(),
        ]);
    }

    public function show(int $id): string|RedirectResponse
    {
        $purchaseRequest = RepositoryServices::purchaseRequestService()->findWithItems($id);

        if ($purchaseRequest === null) {
            return redirect()->to('/procurement/purchase-requests')->with('error', 'Purchase request not found.');
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'procurement.pr_details_viewed',
            'procurement',
            'purchase_request',
            $id,
        );

        return view('procurement/purchase_requests/show', [
            'purchaseRequest' => $purchaseRequest,
        ]);
    }

    public function create()
    {
        return view('procurement/purchase_requests/create', [
            'products' => RepositoryServices::purchaseRequestService()->listFormProducts(),
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
            'products'        => RepositoryServices::purchaseRequestService()->listFormProducts(array_map(
                static fn (array $item): int => (int) ($item['product_id'] ?? 0),
                (array) ($purchaseRequest['items'] ?? []),
            )),
        ]);
    }

    public function itemsCsv(int $id): RedirectResponse|ResponseInterface
    {
        $purchaseRequest = RepositoryServices::purchaseRequestService()->findWithItems($id);

        if ($purchaseRequest === null) {
            return redirect()->to('/procurement/purchase-requests')->with('error', 'Purchase request not found.');
        }

        $rows = $purchaseRequest['items'] ?? [];
        $csv = RepositoryServices::procurementExportPresenter()->purchaseRequestItemsCsv(
            (string) ($purchaseRequest['pr_number'] ?? $id),
            $rows,
        );

        return $this->csvResponse(
            $csv['filename'],
            $csv['headers'],
            $csv['rows'],
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
        $productIds         = (array) $this->request->getPost('product_id');
        $itemNames          = (array) $this->request->getPost('item_name');
        $requestedQuantites = (array) $this->request->getPost('requested_qty');
        $units              = (array) $this->request->getPost('unit');
        $estimatedUnitCosts = (array) $this->request->getPost('estimated_unit_cost');
        $notes              = (array) $this->request->getPost('notes');

        $items = [];

        $rowCount = max(count($productIds), count($itemNames), count($requestedQuantites));

        for ($index = 0; $index < $rowCount; $index++) {
            $items[] = [
                'product_id'          => $productIds[$index] ?? null,
                'item_name'           => $itemNames[$index] ?? null,
                'unit'                => $units[$index] ?? null,
                'requested_qty'       => $requestedQuantites[$index] ?? null,
                'estimated_unit_cost' => $estimatedUnitCosts[$index] ?? null,
                'notes'               => $notes[$index] ?? null,
            ];
        }

        return $items;
    }
}
