<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\Catalog\ProductRepositoryInterface;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(bool $activeOnly = false): array
    {
        return $this->products->listAll($activeOnly);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listActive(): array
    {
        return $this->products->listAll(true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAvailableForIssuance(): array
    {
        return $this->products->listAvailableForIssuance();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOrFail(int $productId, bool $requireActive = true): array
    {
        $product = $this->products->find($productId);

        if ($product === null) {
            throw new \DomainException('Selected product was not found.');
        }

        if ($requireActive && (int) ($product['is_active'] ?? 0) !== 1) {
            throw new \DomainException('Selected product is inactive.');
        }

        return $product;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByNameAndUnit(string $productName, string $unit): ?array
    {
        return $this->products->findByNameAndUnit($productName, $this->normalizeUnit($unit));
    }

    public function create(array $data): int
    {
        $productName = trim((string) ($data['product_name'] ?? ''));
        $unit = $this->normalizeUnit($data['unit'] ?? null);

        if ($productName === '') {
            throw new \InvalidArgumentException('Product name is required.');
        }

        $existing = $this->products->findByNameAndUnit($productName, $unit);
        if ($existing !== null) {
            throw new \DomainException('Product already exists for the selected unit.');
        }

        return $this->products->create([
            'product_code' => $this->generateCode('PRD'),
            'product_name' => $productName,
            'unit'         => $unit,
            'is_active'    => $this->normalizeActive($data['is_active'] ?? 1),
        ]);
    }

    public function update(int $productId, array $data): void
    {
        $product = $this->getOrFail($productId, false);

        $productName = trim((string) ($data['product_name'] ?? ''));
        $unit = $this->normalizeUnit($data['unit'] ?? null);

        if ($productName === '') {
            throw new \InvalidArgumentException('Product name is required.');
        }

        $existing = $this->products->findByNameAndUnit($productName, $unit);
        if ($existing !== null && (int) ($existing['id'] ?? 0) !== $productId) {
            throw new \DomainException('Another product already uses that name and unit.');
        }

        $this->products->update($productId, [
            'product_name' => $productName,
            'unit'         => $unit,
            'is_active'    => $this->normalizeActive($data['is_active'] ?? ($product['is_active'] ?? 1)),
        ]);
    }

    private function generateCode(string $prefix): string
    {
        return $prefix . '-' . strtoupper(substr(md5($prefix . microtime(true) . random_int(1000, 9999)), 0, 8));
    }

    private function normalizeUnit(mixed $value): string
    {
        $unit = trim((string) $value);

        return $unit === '' ? 'unit' : $unit;
    }

    private function normalizeActive(mixed $value): int
    {
        return in_array((string) $value, ['1', 'true', 'on'], true) ? 1 : 0;
    }
}
