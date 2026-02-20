<?php

namespace App\Repositories\EloquentLike\Receiving;

use App\Models\Receiving\ReceivingModel;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use RuntimeException;

class ReceivingRepository implements ReceivingRepositoryInterface
{
    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['status'])) {
            $model->where('status', (string) $filters['status']);
        }

        if (! empty($filters['po_request_id'])) {
            $model->where('po_request_id', (int) $filters['po_request_id']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    public function find(int $id): ?array
    {
        $record = $this->newModel()->find($id);

        return is_array($record) ? $record : null;
    }

    public function findByNumber(string $receivingNumber): ?array
    {
        $record = $this->newModel()->where('receiving_number', $receivingNumber)->first();

        return is_array($record) ? $record : null;
    }

    public function findByPoRequest(int $poRequestId): ?array
    {
        $record = $this->newModel()->where('po_request_id', $poRequestId)->orderBy('id', 'DESC')->first();

        return is_array($record) ? $record : null;
    }

    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create receiving record.');
        }

        return (int) $id;
    }

    public function update(int $id, array $data): bool
    {
        return $this->newModel()->update($id, $data);
    }

    private function newModel(): ReceivingModel
    {
        return new ReceivingModel();
    }
}
