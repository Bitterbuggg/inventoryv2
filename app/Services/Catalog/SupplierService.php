<?php

namespace App\Services\Catalog;

use App\Repositories\Contracts\Catalog\SupplierRepositoryInterface;

class SupplierService
{
    public function __construct(private readonly SupplierRepositoryInterface $suppliers)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(bool $activeOnly = false, ?string $keyword = null): array
    {
        $keyword = trim((string) $keyword);

        return $this->suppliers->listAll($activeOnly, $keyword === '' ? null : $keyword);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listActive(): array
    {
        return $this->suppliers->listAll(true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getOrFail(int $supplierId, bool $requireActive = true): array
    {
        $supplier = $this->suppliers->find($supplierId);

        if ($supplier === null) {
            throw new \DomainException('Selected supplier was not found.');
        }

        if ($requireActive && (int) ($supplier['is_active'] ?? 0) !== 1) {
            throw new \DomainException('Selected supplier is inactive.');
        }

        return $supplier;
    }

    public function create(array $data): int
    {
        $supplierName = trim((string) ($data['supplier_name'] ?? ''));

        if ($supplierName === '') {
            throw new \InvalidArgumentException('Supplier name is required.');
        }

        $existing = $this->suppliers->findByName($supplierName);
        if ($existing !== null) {
            throw new \DomainException('Supplier already exists.');
        }

        return $this->suppliers->create([
            'supplier_code'  => $this->generateCode('SUP'),
            'supplier_name'  => $supplierName,
            'contact_person' => $this->nullableText($data['contact_person'] ?? null),
            'phone'          => $this->nullableText($data['phone'] ?? null),
            'email'          => $this->nullableText($data['email'] ?? null),
            'is_active'      => $this->normalizeActive($data['is_active'] ?? 1),
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByName(string $supplierName): ?array
    {
        return $this->suppliers->findByName(trim($supplierName));
    }

    public function update(int $supplierId, array $data): void
    {
        $supplier = $this->getOrFail($supplierId, false);

        $supplierName = trim((string) ($data['supplier_name'] ?? ''));

        if ($supplierName === '') {
            throw new \InvalidArgumentException('Supplier name is required.');
        }

        $existing = $this->suppliers->findByName($supplierName);
        if ($existing !== null && (int) ($existing['id'] ?? 0) !== $supplierId) {
            throw new \DomainException('Another supplier already uses that name.');
        }

        $this->suppliers->update($supplierId, [
            'supplier_name'  => $supplierName,
            'contact_person' => $this->nullableText($data['contact_person'] ?? ($supplier['contact_person'] ?? null)),
            'phone'          => $this->nullableText($data['phone'] ?? ($supplier['phone'] ?? null)),
            'email'          => $this->nullableText($data['email'] ?? ($supplier['email'] ?? null)),
            'is_active'      => $this->normalizeActive($data['is_active'] ?? ($supplier['is_active'] ?? 1)),
        ]);
    }

    private function generateCode(string $prefix): string
    {
        return $prefix . '-' . strtoupper(substr(md5($prefix . microtime(true) . random_int(1000, 9999)), 0, 8));
    }

    private function nullableText(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function normalizeActive(mixed $value): int
    {
        return in_array((string) $value, ['1', 'true', 'on'], true) ? 1 : 0;
    }
}
