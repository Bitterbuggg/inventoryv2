<?php

use App\Services\Receiving\ReceivingValidationService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReceivingValidationServiceTest extends CIUnitTestCase
{
    public function testValidateItemsPassesForValidQuantities(): void
    {
        $service = new ReceivingValidationService();

        $items = [[
            'purchase_order_item_id' => 11,
            'received_qty'           => 10,
            'accepted_qty'           => 8,
            'rejected_qty'           => 2,
        ]];

        $poItemsById = [
            11 => [
                'id'           => 11,
                'ordered_qty'  => 20,
                'received_qty' => 5,
            ],
        ];

        $errors = $service->validateItems($items, $poItemsById);

        $this->assertSame([], $errors);
    }

    public function testValidateItemsFailsWhenAcceptedRejectedDoNotMatchReceived(): void
    {
        $service = new ReceivingValidationService();

        $items = [[
            'purchase_order_item_id' => 12,
            'received_qty'           => 10,
            'accepted_qty'           => 7,
            'rejected_qty'           => 1,
        ]];

        $poItemsById = [
            12 => [
                'id'           => 12,
                'ordered_qty'  => 30,
                'received_qty' => 0,
            ],
        ];

        $errors = $service->validateItems($items, $poItemsById);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('accepted + rejected must equal received quantity', $errors[0]);
    }

    public function testValidateItemsFailsWhenAcceptedExceedsRemainingQty(): void
    {
        $service = new ReceivingValidationService();

        $items = [[
            'purchase_order_item_id' => 13,
            'received_qty'           => 6,
            'accepted_qty'           => 6,
            'rejected_qty'           => 0,
        ]];

        $poItemsById = [
            13 => [
                'id'           => 13,
                'ordered_qty'  => 10,
                'received_qty' => 5,
            ],
        ];

        $errors = $service->validateItems($items, $poItemsById);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('exceeds remaining PO quantity', $errors[0]);
    }

    public function testValidateItemsFailsWhenExpiryDateIsInThePast(): void
    {
        $service = new ReceivingValidationService();

        $items = [[
            'purchase_order_item_id' => 14,
            'received_qty'           => 3,
            'accepted_qty'           => 3,
            'rejected_qty'           => 0,
            'expiry_date'            => date('Y-m-d', strtotime('-1 day')),
        ]];

        $poItemsById = [
            14 => [
                'id'           => 14,
                'ordered_qty'  => 10,
                'received_qty' => 0,
            ],
        ];

        $errors = $service->validateItems($items, $poItemsById);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('expiry date cannot be in the past', $errors[0]);
    }
}
