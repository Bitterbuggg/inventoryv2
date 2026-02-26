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
                    (string) ($row['on_hand_qty'] ?? '0'),
                    (string) ($row['reserved_qty'] ?? '0'),
                    (string) ($row['available_qty'] ?? '0'),
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

    public function movementsCsv(int $inventoryStockId): RedirectResponse|ResponseInterface
    {
        $stock = RepositoryServices::inventoryQuantityService()->findWithMovements($inventoryStockId);

        if ($stock === null) {
            return redirect()->to('/inventory/quantities')->with('error', 'Inventory stock record not found.');
        }

        $rows = $stock['movements'] ?? [];

        return $this->csvResponse(
            'inventory_stock_movements_' . ((string) ($stock['id'] ?? $inventoryStockId)) . '.csv',
            ['ID', 'Movement Number', 'Type', 'Reference Type', 'Reference ID', 'Qty In', 'Qty Out', 'Balance After', 'Performed At'],
            array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['movement_number'] ?? ''),
                (string) ($row['movement_type'] ?? ''),
                (string) ($row['reference_type'] ?? ''),
                (string) ($row['reference_id'] ?? ''),
                (string) ($row['qty_in'] ?? '0'),
                (string) ($row['qty_out'] ?? '0'),
                (string) ($row['balance_after'] ?? '0'),
                (string) ($row['performed_at'] ?? ''),
            ], $rows),
        );
    }
}
