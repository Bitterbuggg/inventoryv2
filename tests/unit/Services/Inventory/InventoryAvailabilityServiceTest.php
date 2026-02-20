<?php

use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Services\Inventory\InventoryAvailabilityService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class InventoryAvailabilityServiceTest extends CIUnitTestCase
{
    public function testHasSufficientStockAggregatesAcrossAllocations(): void
    {
        $stocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $stocks->method('listForAllocation')->with('Paracetamol', 'box')->willReturn([
            ['id' => 1, 'available_qty' => 2],
            ['id' => 2, 'available_qty' => 3],
        ]);

        $service = new InventoryAvailabilityService($stocks);

        $this->assertTrue($service->hasSufficientStock('Paracetamol', 'box', 4));
        $this->assertFalse($service->hasSufficientStock('Paracetamol', 'box', 6));
    }

    public function testAllocateReturnsExpectedSlices(): void
    {
        $stocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $stocks->method('listForAllocation')->with('Paracetamol', 'box')->willReturn([
            ['id' => 11, 'available_qty' => 2],
            ['id' => 12, 'available_qty' => 3],
        ]);

        $service     = new InventoryAvailabilityService($stocks);
        $allocations = $service->allocate('Paracetamol', 'box', 4);

        $this->assertCount(2, $allocations);
        $this->assertSame(11, $allocations[0]['stock']['id']);
        $this->assertSame(2.0, $allocations[0]['qty']);
        $this->assertSame(2.0, $allocations[1]['qty']);
    }

    public function testAllocateThrowsWhenStockIsInsufficient(): void
    {
        $stocks = $this->createMock(InventoryStockRepositoryInterface::class);
        $stocks->method('listForAllocation')->with('Paracetamol', 'box')->willReturn([
            ['id' => 21, 'available_qty' => 1],
        ]);

        $service = new InventoryAvailabilityService($stocks);

        $this->expectException(DomainException::class);
        $service->allocate('Paracetamol', 'box', 2);
    }
}
