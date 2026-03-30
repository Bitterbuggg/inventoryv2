<?php

namespace App\Controllers\Receiving;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class InventoryQuantityController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $stocks = RepositoryServices::inventoryQuantityService()->list($keyword === '' ? null : $keyword);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'inventory.quantities_viewed',
            'inventory',
            null,
            null,
            ['keyword_used' => $keyword !== ''],
        );

        if ($this->shouldExportCsv()) {
            return $this->csvResponse(
                'inventory_quantities_' . date('Ymd_His') . '.csv',
                ['ID', 'Item', 'Unit', 'Batch', 'Lot', 'Expiry', 'On Hand', 'Reserved', 'Available', 'Avg Cost'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['item_name'] ?? ''),
                    (string) ($row['unit'] ?? ''),
                    (string) ($row['batch_no'] ?? ''),
                    (string) ($row['lot_no'] ?? ''),
                    (string) ($row['expiry_date'] ?? ''),
                    app_format_quantity($row['on_hand_qty'] ?? 0, '0', 3, false),
                    app_format_quantity($row['reserved_qty'] ?? 0, '0', 3, false),
                    app_format_quantity($row['available_qty'] ?? 0, '0', 3, false),
                    number_format((float) ($row['average_unit_cost'] ?? 0), 2, '.', ''),
                ], $stocks),
            );
        }

        return view('inventory/quantities/index', [
            'stocks'  => $stocks,
            'keyword' => $keyword,
        ]);
    }

    public function show(int $inventoryStockId): string|RedirectResponse
    {
        $stock = RepositoryServices::inventoryQuantityService()->findWithMovements($inventoryStockId);

        if ($stock === null) {
            return redirect()->to('/inventory/quantities')->with('error', 'Inventory stock record not found.');
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'inventory.stock_viewed',
            'inventory',
            'inventory_stock',
            $inventoryStockId,
        );

        return view('inventory/quantities/show', [
            'stock' => $stock,
        ]);
    }

    public function adjustOut(int $inventoryStockId): RedirectResponse
    {
        $rules = [
            'qty'    => 'required|is_natural_no_zero',
            'reason' => 'required|in_list[Expired,Damaged,Recall,Lost]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $qty    = (float) $this->request->getPost('qty');
        $reason = (string) $this->request->getPost('reason');

        try {
            $user = auth()->user();
            RepositoryServices::inventoryQuantityService()->manualAdjustmentOut(
                $inventoryStockId,
                $qty,
                (int) ($user->id ?? 0),
                $reason
            );

            return redirect()->to('/inventory/quantities')->with('message', "Stock disposal recorded for {$qty} units (Reason: {$reason}).");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
