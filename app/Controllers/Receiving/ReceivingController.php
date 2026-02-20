<?php

namespace App\Controllers\Receiving;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use function auth;

class ReceivingController extends BaseController
{
    public function index(): string
    {
        $status = trim((string) $this->request->getGet('status'));

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.list_viewed',
            'receiving',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        return view('receiving/index', [
            'receivings'            => RepositoryServices::receivingService()->list($status === '' ? null : $status),
            'convertiblePoRequests' => RepositoryServices::receivingService()->listConvertiblePoRequests(),
            'status'                => $status,
        ]);
    }

    public function createFromPoRequest(int $poRequestId): string|RedirectResponse
    {
        try {
            $conversion = RepositoryServices::receivingService()->buildConversionData($poRequestId);
        } catch (\Throwable $exception) {
            return redirect()->to('/receiving')->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.conversion_viewed',
            'receiving',
            'po_request',
            $poRequestId,
        );

        return view('receiving/conversion', $conversion);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'po_request_id' => 'required|integer|greater_than[0]',
            'received_date' => 'required|valid_date[Y-m-d]',
            'remarks'       => 'permit_empty|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $receivingId = RepositoryServices::receivingService()->createDraft([
                'po_request_id'      => (int) $this->request->getPost('po_request_id'),
                'received_date'      => (string) $this->request->getPost('received_date'),
                'delivery_reference' => $this->request->getPost('delivery_reference'),
                'remarks'            => $this->request->getPost('remarks'),
                'received_by'        => $this->currentUserId(),
                'items'              => $this->extractItemsFromPost(),
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.draft_created',
            'receiving',
            'receiving',
            $receivingId,
        );

        return redirect()->to('/receiving/' . $receivingId)->with('message', 'Receiving draft created.');
    }

    public function show(int $id): string|RedirectResponse
    {
        $receiving = RepositoryServices::receivingService()->findWithItems($id);

        if ($receiving === null) {
            return redirect()->to('/receiving')->with('error', 'Receiving not found.');
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.details_viewed',
            'receiving',
            'receiving',
            $id,
        );

        return view('receiving/show', [
            'receiving' => $receiving,
        ]);
    }

    public function post(int $id): RedirectResponse
    {
        try {
            RepositoryServices::receivingService()->post($id, $this->currentUserId());
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.posted',
            'receiving',
            'receiving',
            $id,
        );

        return redirect()->to('/receiving/' . $id)->with('message', 'Receiving posted and inventory updated.');
    }

    public function void(int $id): RedirectResponse
    {
        $rules = [
            'reason' => 'required|min_length[3]|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::receivingService()->void(
                $id,
                $this->currentUserId(),
                (string) $this->request->getPost('reason'),
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.voided',
            'receiving',
            'receiving',
            $id,
        );

        return redirect()->to('/receiving')->with('message', 'Receiving draft voided.');
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
        $purchaseOrderItemIds = (array) $this->request->getPost('purchase_order_item_id');
        $itemNames            = (array) $this->request->getPost('item_name');
        $units                = (array) $this->request->getPost('unit');
        $receivedQty          = (array) $this->request->getPost('received_qty');
        $acceptedQty          = (array) $this->request->getPost('accepted_qty');
        $rejectedQty          = (array) $this->request->getPost('rejected_qty');
        $batchNos             = (array) $this->request->getPost('batch_no');
        $lotNos               = (array) $this->request->getPost('lot_no');
        $expiryDates          = (array) $this->request->getPost('expiry_date');
        $unitCosts            = (array) $this->request->getPost('unit_cost');
        $remarks              = (array) $this->request->getPost('item_remarks');

        $items = [];

        foreach ($purchaseOrderItemIds as $index => $purchaseOrderItemId) {
            $items[] = [
                'purchase_order_item_id' => $purchaseOrderItemId,
                'item_name'              => $itemNames[$index] ?? '',
                'unit'                   => $units[$index] ?? 'unit',
                'received_qty'           => $receivedQty[$index] ?? 0,
                'accepted_qty'           => $acceptedQty[$index] ?? 0,
                'rejected_qty'           => $rejectedQty[$index] ?? 0,
                'batch_no'               => $batchNos[$index] ?? null,
                'lot_no'                 => $lotNos[$index] ?? null,
                'expiry_date'            => $expiryDates[$index] ?? null,
                'unit_cost'              => $unitCosts[$index] ?? 0,
                'remarks'                => $remarks[$index] ?? null,
            ];
        }

        return $items;
    }
}
