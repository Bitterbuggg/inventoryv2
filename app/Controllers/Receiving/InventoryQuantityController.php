<?php

namespace App\Controllers\Receiving;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;

class InventoryQuantityController extends BaseController
{
    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));

        RepositoryServices::analyticsService()->trackCurrentUser(
            'inventory.quantities_viewed',
            'inventory',
            null,
            null,
            ['keyword_used' => $keyword !== ''],
        );

        return view('inventory/quantities/index', [
            'stocks'  => RepositoryServices::inventoryQuantityService()->list($keyword === '' ? null : $keyword),
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
}
