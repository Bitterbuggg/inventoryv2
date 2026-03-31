<?php

use App\Repositories\Contracts\Catalog\SupplierRepositoryInterface;
use App\Services\Catalog\SupplierService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class SupplierServiceTest extends CIUnitTestCase
{
    public function testListAllTrimsSearchKeywordBeforeQueryingRepository(): void
    {
        $suppliers = $this->createMock(SupplierRepositoryInterface::class);
        $expected = [['id' => 4, 'supplier_name' => 'Northwind']];

        $suppliers->expects($this->once())
            ->method('listAll')
            ->with(false, 'Northwind')
            ->willReturn($expected);

        $service = new SupplierService($suppliers);

        $this->assertSame($expected, $service->listAll(false, '  Northwind  '));
    }

    public function testCreateRejectsDuplicateSupplierName(): void
    {
        $suppliers = $this->createMock(SupplierRepositoryInterface::class);

        $suppliers->expects($this->once())
            ->method('findByName')
            ->with('Supplier A')
            ->willReturn(['id' => 7, 'supplier_name' => 'Supplier A']);

        $suppliers->expects($this->never())->method('create');

        $service = new SupplierService($suppliers);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Supplier already exists.');

        $service->create([
            'supplier_name' => 'Supplier A',
            'is_active' => '1',
        ]);
    }

    public function testUpdateNormalizesOptionalFieldsBeforeSaving(): void
    {
        $suppliers = $this->createMock(SupplierRepositoryInterface::class);

        $suppliers->expects($this->once())
            ->method('find')
            ->with(3)
            ->willReturn([
                'id' => 3,
                'supplier_name' => 'Supplier B',
                'contact_person' => 'Old Contact',
                'phone' => '111',
                'email' => 'old@example.test',
                'is_active' => 1,
            ]);

        $suppliers->expects($this->once())
            ->method('findByName')
            ->with('Supplier B')
            ->willReturn(['id' => 3, 'supplier_name' => 'Supplier B']);

        $suppliers->expects($this->once())
            ->method('update')
            ->with(3, [
                'supplier_name' => 'Supplier B',
                'contact_person' => null,
                'phone' => null,
                'email' => null,
                'is_active' => 0,
            ]);

        $service = new SupplierService($suppliers);
        $service->update(3, [
            'supplier_name' => 'Supplier B',
            'contact_person' => '   ',
            'phone' => '',
            'email' => '',
            'is_active' => '0',
        ]);

        $this->assertTrue(true);
    }

    public function testGetOrFailRejectsInactiveSupplierWhenRequired(): void
    {
        $suppliers = $this->createMock(SupplierRepositoryInterface::class);

        $suppliers->expects($this->once())
            ->method('find')
            ->with(6)
            ->willReturn(['id' => 6, 'supplier_name' => 'Legacy Supplier', 'is_active' => 0]);

        $service = new SupplierService($suppliers);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Selected supplier is inactive.');

        $service->getOrFail(6);
    }
}
