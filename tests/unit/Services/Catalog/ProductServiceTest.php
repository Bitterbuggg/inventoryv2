<?php

use App\Repositories\Contracts\Catalog\ProductRepositoryInterface;
use App\Services\Catalog\ProductService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProductServiceTest extends CIUnitTestCase
{
    public function testListAllTrimsSearchKeywordBeforeQueryingRepository(): void
    {
        $products = $this->createMock(ProductRepositoryInterface::class);
        $expected = [['id' => 11, 'product_name' => 'Syringe']];

        $products->expects($this->once())
            ->method('listAll')
            ->with(false, 'Syringe')
            ->willReturn($expected);

        $service = new ProductService($products);

        $this->assertSame($expected, $service->listAll(false, '  Syringe  '));
    }

    public function testCreateRejectsDuplicateProductNameAndUnit(): void
    {
        $products = $this->createMock(ProductRepositoryInterface::class);

        $products->expects($this->once())
            ->method('findByNameAndUnit')
            ->with('Paracetamol', 'box')
            ->willReturn(['id' => 8, 'product_name' => 'Paracetamol', 'unit' => 'box']);

        $products->expects($this->never())->method('create');

        $service = new ProductService($products);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Product already exists');

        $service->create([
            'product_name' => 'Paracetamol',
            'unit' => 'box',
            'is_active' => '1',
        ]);
    }

    public function testUpdateRejectsDuplicateNameUsedByAnotherProduct(): void
    {
        $products = $this->createMock(ProductRepositoryInterface::class);

        $products->expects($this->once())
            ->method('find')
            ->with(5)
            ->willReturn(['id' => 5, 'product_name' => 'Bandage', 'unit' => 'pack', 'is_active' => 1]);

        $products->expects($this->once())
            ->method('findByNameAndUnit')
            ->with('Bandage', 'box')
            ->willReturn(['id' => 9, 'product_name' => 'Bandage', 'unit' => 'box', 'is_active' => 1]);

        $products->expects($this->never())->method('update');

        $service = new ProductService($products);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Another product already uses that name and unit.');

        $service->update(5, [
            'product_name' => 'Bandage',
            'unit' => 'box',
            'is_active' => '1',
        ]);
    }

    public function testGetOrFailRejectsInactiveProductWhenRequired(): void
    {
        $products = $this->createMock(ProductRepositoryInterface::class);

        $products->expects($this->once())
            ->method('find')
            ->with(4)
            ->willReturn(['id' => 4, 'product_name' => 'Legacy Item', 'unit' => 'box', 'is_active' => 0]);

        $service = new ProductService($products);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Selected product is inactive.');

        $service->getOrFail(4);
    }
}
