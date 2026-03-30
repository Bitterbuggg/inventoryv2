<?php

namespace App\Services\Procurement;

use App\Services\Catalog\ProductService;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Services\Shared\ApprovalWorkflowService;
use DomainException;
use InvalidArgumentException;
use RuntimeException;

class PurchaseRequestService
{
    public function __construct(
        private readonly PurchaseRequestRepositoryInterface $purchaseRequests,
        private readonly ApprovalWorkflowService $approvalWorkflow,
        private readonly ?ProductService $products = null,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $status = null): array
    {
        $filters = [];

        if ($status !== null && $status !== '') {
            $filters['status'] = $status;
        }

        return $this->purchaseRequests->list($filters);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithItems(int $purchaseRequestId): ?array
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            return null;
        }

        $purchaseRequest['items'] = $this->purchaseRequests->listItems($purchaseRequestId);

        return $purchaseRequest;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listFormProducts(array $includeProductIds = []): array
    {
        if ($this->products === null) {
            throw new RuntimeException('Product catalog service is unavailable.');
        }

        $products = $this->products->listActive();
        $includeProductIds = array_values(array_unique(array_filter(
            array_map(static fn (mixed $id): int => (int) $id, $includeProductIds),
            static fn (int $id): bool => $id > 0,
        )));

        if ($includeProductIds === []) {
            return $products;
        }

        $productsById = [];
        foreach ($products as $product) {
            $productId = (int) ($product['id'] ?? 0);
            if ($productId > 0) {
                $productsById[$productId] = $product;
            }
        }

        foreach ($this->products->listAll() as $product) {
            $productId = (int) ($product['id'] ?? 0);
            if ($productId <= 0 || ! in_array($productId, $includeProductIds, true) || isset($productsById[$productId])) {
                continue;
            }

            $productsById[$productId] = $product;
        }

        $products = array_values($productsById);
        usort($products, static function (array $left, array $right): int {
            $leftKey = strtolower(trim((string) ($left['product_name'] ?? '')) . '|' . trim((string) ($left['unit'] ?? '')));
            $rightKey = strtolower(trim((string) ($right['product_name'] ?? '')) . '|' . trim((string) ($right['unit'] ?? '')));

            return $leftKey <=> $rightKey;
        });

        return $products;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $requestedBy = (int) ($data['requested_by'] ?? 0);
        $requestDate = trim((string) ($data['request_date'] ?? ''));

        if ($requestedBy <= 0) {
            throw new InvalidArgumentException('Requester is required.');
        }

        if ($requestDate === '') {
            throw new InvalidArgumentException('Request date is required.');
        }

        $items = $this->normalizeItems($data['items'] ?? []);

        if ($items === []) {
            throw new DomainException('At least one valid item is required.');
        }

        $purchaseRequestId = $this->purchaseRequests->create([
            'pr_number'        => $this->generatePrNumber(),
            'requested_by'     => $requestedBy,
            'request_date'     => $requestDate,
            'needed_date'      => $this->nullableDate($data['needed_date'] ?? null),
            'remarks'          => $this->nullableText($data['remarks'] ?? null),
            'status'           => 'draft',
            'submitted_at'     => null,
            'approved_by'      => null,
            'approved_at'      => null,
            'rejected_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);

        $this->purchaseRequests->addItems($purchaseRequestId, $items);

        return $purchaseRequestId;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $purchaseRequestId, array $data): void
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'draft') {
            throw new DomainException('Only draft purchase requests can be edited.');
        }

        $requestDate = trim((string) ($data['request_date'] ?? ''));

        if ($requestDate === '') {
            throw new InvalidArgumentException('Request date is required.');
        }

        $items = $this->normalizeItems($data['items'] ?? []);

        if ($items === []) {
            throw new DomainException('At least one valid item is required.');
        }

        $this->purchaseRequests->update($purchaseRequestId, [
            'request_date' => $requestDate,
            'needed_date'  => $this->nullableDate($data['needed_date'] ?? null),
            'remarks'      => $this->nullableText($data['remarks'] ?? null),
        ]);

        $this->purchaseRequests->replaceItems($purchaseRequestId, $items);
    }

    public function submit(int $purchaseRequestId): void
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request not found.');
        }

        if (($purchaseRequest['status'] ?? '') !== 'draft') {
            throw new DomainException('Only draft purchase requests can be submitted.');
        }

        $items = $this->purchaseRequests->listItems($purchaseRequestId);
        if ($items === []) {
            throw new DomainException('Cannot submit purchase request without items.');
        }

        $now = date('Y-m-d H:i:s');

        $this->purchaseRequests->update($purchaseRequestId, [
            'status'       => 'submitted',
            'submitted_at' => $now,
        ]);

        $this->approvalWorkflow->ensurePendingApproval('purchase_request', $purchaseRequestId);
    }

    public function cancel(int $purchaseRequestId, int $actorId): void
    {
        $purchaseRequest = $this->purchaseRequests->find($purchaseRequestId);

        if ($purchaseRequest === null) {
            throw new DomainException('Purchase request not found.');
        }

        $status = (string) ($purchaseRequest['status'] ?? '');

        if (! in_array($status, ['draft', 'submitted'], true)) {
            throw new DomainException('Only draft or submitted purchase requests can be cancelled.');
        }

        $now = date('Y-m-d H:i:s');

        $this->purchaseRequests->update($purchaseRequestId, [
            'status'           => 'cancelled',
            'rejected_by'      => $actorId,
            'rejected_at'      => $now,
            'rejection_reason' => 'Cancelled by user.',
        ]);

        $this->approvalWorkflow->rejectPendingApprovalIfExists(
            'purchase_request',
            $purchaseRequestId,
            $actorId,
            'Cancelled by user.',
        );
    }

    private function generatePrNumber(): string
    {
        do {
            $number = 'PR-' . date('Ymd-His') . '-' . substr((string)round(microtime(true) * 1000), -4);
        } while ($this->purchaseRequests->findByNumber($number) !== null);

        return $number;
    }

    /**
     * @param mixed $items
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $normalized = [];
        $seenKeys   = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $product = $this->resolveProduct($item);
            $productId = (int) ($product['id'] ?? 0) ?: null;
            $itemName = $product !== null
                ? trim((string) ($product['product_name'] ?? ''))
                : trim((string) ($item['item_name'] ?? ''));
            $unit = $product !== null
                ? (trim((string) ($product['unit'] ?? 'unit')) ?: 'unit')
                : (trim((string) ($item['unit'] ?? 'unit')) ?: 'unit');

            if ($itemName === '') {
                continue;
            }

            $rawQty = $item['requested_qty'] ?? 0;

            if (! is_numeric($rawQty)) {
                throw new DomainException('Requested quantity must be a valid number.');
            }

            $qty = (float) $rawQty;

            if ($qty <= 0) {
                throw new DomainException('Requested quantity must be greater than zero.');
            }

            if (abs($qty - round($qty)) > 0.00001) {
                throw new DomainException('Requested quantity must be a whole number.');
            }

            $key = $productId !== null
                ? 'product:' . $productId
                : strtolower($itemName) . '|' . strtolower($unit);

            if (isset($seenKeys[$key])) {
                throw new DomainException('Duplicate purchase request items are not allowed.');
            }

            $seenKeys[$key] = true;

            $normalized[] = [
                'product_id'          => $productId,
                'item_name'           => $itemName,
                'requested_qty'       => (float) round($qty),
                'approved_qty'        => null,
                'unit'                => $unit,
                'estimated_unit_cost' => $this->nullableNumeric($item['estimated_unit_cost'] ?? null),
                'notes'               => $this->nullableText($item['notes'] ?? null),
            ];
        }

        return $normalized;
    }

    private function nullableDate(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function nullableText(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function nullableNumeric(mixed $value): ?float
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return null;
        }

        return (float) $raw;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>|null
     */
    private function resolveProduct(array $item): ?array
    {
        $productId = (int) ($item['product_id'] ?? 0);
        if ($this->products !== null && $productId > 0) {
            return $this->products->getOrFail($productId);
        }

        $itemName = trim((string) ($item['item_name'] ?? ''));
        if ($this->products === null || $itemName === '') {
            return null;
        }

        return $this->products->findByNameAndUnit(
            $itemName,
            trim((string) ($item['unit'] ?? 'unit')) ?: 'unit',
        );
    }
}

