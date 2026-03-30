<?php

namespace App\Services\Receiving;

use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use DomainException;

class ReceivingWorkflowContextService
{
    public function __construct(
        private readonly ReceivingRepositoryInterface $receivings,
        private readonly ReceivingItemRepositoryInterface $receivingItems,
        private readonly PoRequestRepositoryInterface $poRequests,
        private readonly PurchaseOrderRepositoryInterface $purchaseOrders,
    ) {
    }

    /**
     * @return array{
     *     po_request: array<string, mixed>,
     *     purchase_order: array<string, mixed>,
     *     purchase_order_items: array<int, array<string, mixed>>,
     *     purchase_order_items_by_id: array<int, array<string, mixed>>,
     *     remaining_items: array<int, array<string, mixed>>
     * }
     */
    public function buildConversionContext(int $poRequestId): array
    {
        $poRequest = $this->poRequests->find($poRequestId);

        if ($poRequest === null) {
            throw new DomainException('PO request not found.');
        }

        if (($poRequest['status'] ?? '') !== 'approved') {
            throw new DomainException('Only approved PO requests can be converted to receiving.');
        }

        $existingReceiving = $this->receivings->findByPoRequest($poRequestId);
        if ($existingReceiving !== null && ($existingReceiving['status'] ?? '') !== 'voided') {
            throw new DomainException('A receiving record already exists for this PO request.');
        }

        $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new DomainException('Purchase order for PO request was not found.');
        }

        $purchaseOrderItems = $this->purchaseOrders->listItems($purchaseOrderId);

        if ($purchaseOrderItems === []) {
            throw new DomainException('Purchase order has no items to receive.');
        }

        $remainingItems = $this->filterRemainingItems($purchaseOrderItems);

        if ($remainingItems === []) {
            throw new DomainException('No remaining quantities are available for receiving.');
        }

        return [
            'po_request'               => $poRequest,
            'purchase_order'           => $purchaseOrder,
            'purchase_order_items'     => $purchaseOrderItems,
            'purchase_order_items_by_id' => $this->mapPurchaseOrderItems($purchaseOrderItems),
            'remaining_items'          => $remainingItems,
        ];
    }

    /**
     * @return array{
     *     receiving: array<string, mixed>,
     *     purchase_order: array<string, mixed>,
     *     items: array<int, array<string, mixed>>,
     *     purchase_order_items: array<int, array<string, mixed>>,
     *     purchase_order_items_by_id: array<int, array<string, mixed>>
     * }
     */
    public function buildDraftContext(int $receivingId): array
    {
        $receiving = $this->receivings->find($receivingId);

        if ($receiving === null) {
            throw new DomainException('Receiving record not found.');
        }

        $purchaseOrderId = (int) ($receiving['purchase_order_id'] ?? 0);
        $purchaseOrder = $this->purchaseOrders->find($purchaseOrderId);

        if ($purchaseOrder === null) {
            throw new DomainException('Purchase order for receiving was not found.');
        }

        $purchaseOrderItems = $this->purchaseOrders->listItems($purchaseOrderId);

        return [
            'receiving'                 => $receiving,
            'purchase_order'            => $purchaseOrder,
            'items'                     => $this->receivingItems->listByReceiving($receivingId),
            'purchase_order_items'      => $purchaseOrderItems,
            'purchase_order_items_by_id' => $this->mapPurchaseOrderItems($purchaseOrderItems),
        ];
    }

    /**
     * @return array{
     *     receiving: array<string, mixed>,
     *     po_request: array<string, mixed>,
     *     purchase_order: array<string, mixed>,
     *     items: array<int, array<string, mixed>>,
     *     purchase_order_items: array<int, array<string, mixed>>,
     *     purchase_order_items_by_id: array<int, array<string, mixed>>
     * }
     */
    public function buildPostingContext(int $receivingId): array
    {
        $context = $this->buildDraftContext($receivingId);
        $receiving = $context['receiving'];

        if (($receiving['status'] ?? '') !== 'draft') {
            throw new DomainException('Only draft receiving records can be posted.');
        }

        $poRequest = $this->poRequests->find((int) ($receiving['po_request_id'] ?? 0));
        if ($poRequest === null) {
            throw new DomainException('PO request for receiving was not found.');
        }

        if (($poRequest['status'] ?? '') !== 'converting') {
            throw new DomainException('PO request must be in converting status before posting receiving.');
        }

        if ($context['items'] === []) {
            throw new DomainException('Receiving has no items to post.');
        }

        $context['po_request'] = $poRequest;

        return $context;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     *
     * @return array<int, array<string, mixed>>
     */
    private function filterRemainingItems(array $items): array
    {
        $remainingItems = [];

        foreach ($items as $item) {
            $orderedQty = (float) ($item['ordered_qty'] ?? 0);
            $receivedQty = (float) ($item['received_qty'] ?? 0);

            if (($orderedQty - $receivedQty) > 0.0005) {
                $remainingItems[] = $item;
            }
        }

        return $remainingItems;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     *
     * @return array<int, array<string, mixed>>
     */
    private function mapPurchaseOrderItems(array $items): array
    {
        $map = [];

        foreach ($items as $item) {
            $itemId = (int) ($item['id'] ?? 0);

            if ($itemId > 0) {
                $map[$itemId] = $item;
            }
        }

        return $map;
    }
}
