<?php

namespace App\Repositories\EloquentLike\Receiving;

use App\Models\Receiving\ReceivingItemModel;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;

class ReceivingItemRepository implements ReceivingItemRepositoryInterface
{
    public function addItems(int $receivingId, array $items): void
    {
        if ($items === []) {
            return;
        }

        $rows = [];

        foreach ($items as $item) {
            $rows[] = [
                'receiving_id'          => $receivingId,
                'purchase_order_item_id' => $item['purchase_order_item_id'] ?? 0,
                'item_name'             => $item['item_name'] ?? '',
                'unit'                  => $item['unit'] ?? 'unit',
                'received_qty'          => $item['received_qty'] ?? 0,
                'accepted_qty'          => $item['accepted_qty'] ?? 0,
                'rejected_qty'          => $item['rejected_qty'] ?? 0,
                'batch_no'              => $item['batch_no'] ?? null,
                'lot_no'                => $item['lot_no'] ?? null,
                'expiry_date'           => $item['expiry_date'] ?? null,
                'unit_cost'             => $item['unit_cost'] ?? 0,
                'line_total'            => $item['line_total'] ?? 0,
                'remarks'               => $item['remarks'] ?? null,
            ];
        }

        $this->newModel()->insertBatch($rows);
    }

    public function listByReceiving(int $receivingId): array
    {
        return $this->newModel()
            ->where('receiving_id', $receivingId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    public function sumAcceptedForPoItem(int $purchaseOrderItemId): float
    {
        $result = $this->newModel()
            ->selectSum('accepted_qty', 'accepted_total')
            ->where('purchase_order_item_id', $purchaseOrderItemId)
            ->first();

        if (! is_array($result)) {
            return 0.0;
        }

        return (float) ($result['accepted_total'] ?? 0);
    }

    private function newModel(): ReceivingItemModel
    {
        return new ReceivingItemModel();
    }
}
