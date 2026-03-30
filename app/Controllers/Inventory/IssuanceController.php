<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;
use function auth;

class IssuanceController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $status = trim((string) $this->request->getGet('status'));
        $issuances = RepositoryServices::issuanceService()->list($status === '' ? null : $status);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'inventory.issuance_list_viewed',
            'inventory',
            null,
            null,
            ['status_filter' => $status === '' ? 'all' : $status],
        );

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'issuances_' . date('Ymd_His') . '.csv',
                ['ID', 'Issuance Number', 'Requestor ID', 'Issue Date', 'Department', 'Status'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['issuance_number'] ?? ''),
                    (string) ($row['requestor_id'] ?? ''),
                    (string) ($row['issue_date'] ?? ''),
                    (string) ($row['department'] ?? ''),
                    (string) ($row['status'] ?? ''),
                ], $issuances),
            );
        }

        return view('inventory/issuance/index', [
            'issuances' => $issuances,
            'status'    => $status,
        ]);
    }

    public function create(): string
    {
        return view('inventory/issuance/create', [
            'products' => RepositoryServices::issuanceService()->listFormProducts(),
        ]);
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

        RepositoryServices::analyticsService()->trackCurrentUser(
            'issuance.draft_created',
            'inventory',
            'issuance',
            $issuanceId,
        );

        return redirect()->to('/inventory/issuance/' . $issuanceId)->with('message', 'Issuance draft created.');
    }

    public function show(int $id): string|RedirectResponse
    {
        $issuance = RepositoryServices::issuanceService()->findWithItems($id);

        if ($issuance === null) {
            return redirect()->to('/inventory/issuance')->with('error', 'Issuance record not found.');
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'issuance.details_viewed',
            'inventory',
            'issuance',
            $id,
        );

        return view('inventory/issuance/show', [
            'issuance' => $issuance,
        ]);
    }

    public function itemsCsv(int $id): RedirectResponse|ResponseInterface
    {
        $issuance = RepositoryServices::issuanceService()->findWithItems($id);

        if ($issuance === null) {
            return redirect()->to('/inventory/issuance')->with('error', 'Issuance record not found.');
        }

        $rows = $issuance['items'] ?? [];

        return $this->csvResponse(
            'issuance_items_' . ((string) ($issuance['issuance_number'] ?? $id)) . '.csv',
            ['ID', 'Item', 'Unit', 'Requested Qty', 'Issued Qty', 'Unit Cost', 'Line Total', 'Stock ID'],
            array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['item_name'] ?? ''),
                (string) ($row['unit'] ?? ''),
                (string) ($row['requested_qty'] ?? '0'),
                (string) ($row['issued_qty'] ?? '0'),
                number_format((float) ($row['unit_cost'] ?? 0), 2, '.', ''),
                number_format((float) ($row['line_total'] ?? 0), 2, '.', ''),
                (string) ($row['inventory_stock_id'] ?? ''),
            ], $rows),
        );
    }

    public function allocationsCsv(int $id)
    {
        $issuance = RepositoryServices::issuanceService()->findWithItems($id);

        if ($issuance === null) {
            return redirect()->to('/inventory/issuance')->with('error', 'Issuance record not found.');
        }

        $rows = $issuance['allocations'] ?? [];
        $filename = 'issuance_allocations_' . ((string) ($issuance['issuance_number'] ?? $id)) . '.csv';

        $handle = fopen('php://temp', 'r+');
        if ($handle === false) {
            return redirect()->to('/inventory/issuance/' . $id)->with('error', 'Unable to generate CSV file.');
        }

        fputcsv($handle, ['Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'Qty Issued', 'Unit Cost', 'Line Total']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                (string) ($row['item_name'] ?? ''),
                (string) ($row['unit'] ?? ''),
                (string) ($row['batch_no'] ?? ''),
                (string) ($row['lot_no'] ?? ''),
                (string) ($row['expiry_date'] ?? ''),
                (string) ($row['qty_issued'] ?? '0'),
                number_format((float) ($row['unit_cost'] ?? 0), 2, '.', ''),
                number_format((float) ($row['line_total'] ?? 0), 2, '.', ''),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        if ($csv === false) {
            return redirect()->to('/inventory/issuance/' . $id)->with('error', 'Unable to generate CSV file.');
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    public function submit(int $id): RedirectResponse
    {
        try {
            RepositoryServices::issuanceService()->submit($id, $this->currentUserId());
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'issuance.submitted',
            'inventory',
            'issuance',
            $id,
        );

        return redirect()->to('/inventory/issuance/' . $id)->with('message', 'Issuance submitted for approval.');
    }

    public function release(int $id): RedirectResponse
    {
        try {
            RepositoryServices::issuanceReleaseService()->release($id, $this->currentUserId());
        } catch (\Throwable $exception) {
            RepositoryServices::analyticsService()->trackCurrentUser(
                'issuance.release_failed',
                'inventory',
                'issuance',
                $id,
                ['error' => $exception->getMessage()],
            );

            return redirect()->back()->with('error', $exception->getMessage());
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'issuance.released',
            'inventory',
            'issuance',
            $id,
        );

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

        RepositoryServices::analyticsService()->trackCurrentUser(
            'issuance.cancelled',
            'inventory',
            'issuance',
            $id,
        );

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
        $productIds    = (array) $this->request->getPost('product_id');
        $itemNames     = (array) $this->request->getPost('item_name');
        $units         = (array) $this->request->getPost('unit');
        $requestedQtys = (array) $this->request->getPost('requested_qty');
        $remarks       = (array) $this->request->getPost('item_remarks');

        $items = [];

        $rowCount = max(count($productIds), count($itemNames), count($requestedQtys));

        for ($index = 0; $index < $rowCount; $index++) {
            $items[] = [
                'product_id'    => $productIds[$index] ?? null,
                'item_name'     => $itemNames[$index] ?? null,
                'unit'          => $units[$index] ?? null,
                'requested_qty' => $requestedQtys[$index] ?? 0,
                'remarks'       => $remarks[$index] ?? null,
            ];
        }

        return $items;
    }
}
