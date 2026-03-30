<?php

namespace App\Repositories\EloquentLike\Inventory;

use App\Models\Inventory\IssuanceItemModel;
use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;

class IssuanceItemRepository implements IssuanceItemRepositoryInterface
{
    public function addItems(int $issuanceId, array $items): void
    {
        if ($items === []) {
            return;
        }

        $rows = [];

        foreach ($items as $item) {
            $row = [
                'issuance_id'        => $issuanceId,
                'item_name'          => $item['item_name'] ?? '',
                'unit'               => $item['unit'] ?? 'unit',
                'inventory_stock_id' => $item['inventory_stock_id'] ?? null,
                'requested_qty'      => $item['requested_qty'] ?? 0,
                'issued_qty'         => $item['issued_qty'] ?? 0,
                'unit_cost'          => $item['unit_cost'] ?? 0,
                'line_total'         => $item['line_total'] ?? 0,
                'remarks'            => $item['remarks'] ?? null,
            ];

            if (($item['product_id'] ?? null) !== null) {
                $row['product_id'] = $item['product_id'];
            }

            $rows[] = $row;
        }

        $this->newModel()->insertBatch($rows);
    }

    public function listByIssuance(int $issuanceId): array
    {
        return $this->newModel()
            ->where('issuance_id', $issuanceId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): IssuanceItemModel
    {
        return new IssuanceItemModel();
    }
}
