<?php

namespace App\Services\Receiving;

class ReceivingValidationService
{
    /**
     * @param array<int, array<string, mixed>> $items
     * @param array<int, array<string, mixed>> $purchaseOrderItemsById
     *
     * @return array<int, string>
     */
    public function validateItems(array $items, array $purchaseOrderItemsById): array
    {
        $errors         = [];
        $acceptedByLine = [];
        $todayDate      = date('Y-m-d');

        foreach ($items as $index => $item) {
            $lineNumber = $index + 1;
            $prefix     = 'Item line ' . $lineNumber . ': ';

            $purchaseOrderItemId = (int) ($item['purchase_order_item_id'] ?? 0);
            if ($purchaseOrderItemId <= 0) {
                $errors[] = $prefix . 'purchase order item is required.';
                continue;
            }

            $purchaseOrderItem = $purchaseOrderItemsById[$purchaseOrderItemId] ?? null;
            if ($purchaseOrderItem === null) {
                $errors[] = $prefix . 'purchase order item was not found.';
                continue;
            }

            $receivedQty = (float) ($item['received_qty'] ?? 0);
            $acceptedQty = (float) ($item['accepted_qty'] ?? 0);
            $rejectedQty = (float) ($item['rejected_qty'] ?? 0);
            $expiryDate  = trim((string) ($item['expiry_date'] ?? ''));

            if ($receivedQty <= 0) {
                $errors[] = $prefix . 'received quantity must be greater than zero.';
                continue;
            }

            if ($acceptedQty < 0 || $rejectedQty < 0) {
                $errors[] = $prefix . 'accepted and rejected quantities cannot be negative.';
                continue;
            }

            if (abs(($acceptedQty + $rejectedQty) - $receivedQty) > 0.0005) {
                $errors[] = $prefix . 'accepted + rejected must equal received quantity.';
                continue;
            }

            if ($expiryDate !== '') {
                $parsedExpiry = \DateTimeImmutable::createFromFormat('Y-m-d', $expiryDate);
                $dateErrors = \DateTimeImmutable::getLastErrors();
                $isExpiryValid = $parsedExpiry !== false
                    && (int) ($dateErrors['warning_count'] ?? 0) === 0
                    && (int) ($dateErrors['error_count'] ?? 0) === 0;

                if (! $isExpiryValid) {
                    $errors[] = $prefix . 'expiry date must be a valid YYYY-MM-DD date.';
                    continue;
                }

                if ($expiryDate < $todayDate) {
                    $errors[] = $prefix . 'expiry date cannot be in the past.';
                    continue;
                }
            }

            $orderedQty      = (float) ($purchaseOrderItem['ordered_qty'] ?? 0);
            $alreadyReceived = (float) ($purchaseOrderItem['received_qty'] ?? 0);
            $inBatchAccepted = (float) ($acceptedByLine[$purchaseOrderItemId] ?? 0);
            $remainingQty    = $orderedQty - $alreadyReceived - $inBatchAccepted;

            if ($acceptedQty > $remainingQty + 0.0005) {
                $errors[] = $prefix . 'accepted quantity exceeds remaining PO quantity.';
                continue;
            }

            $acceptedByLine[$purchaseOrderItemId] = $inBatchAccepted + $acceptedQty;
        }

        return $errors;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     * @param array<int, array<string, mixed>> $purchaseOrderItemsById
     */
    public function assertValid(array $items, array $purchaseOrderItemsById): void
    {
        $errors = $this->validateItems($items, $purchaseOrderItemsById);

        if ($errors !== []) {
            throw new \DomainException($errors[0]);
        }
    }
}
