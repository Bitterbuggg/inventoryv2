<?php

namespace App\Repositories\EloquentLike\Shared;

use App\Models\Shared\AuditLogModel;
use App\Repositories\Contracts\Shared\AuditLogRepositoryInterface;
use RuntimeException;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function create(array $data): int
    {
        $id = $this->newModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create audit log entry.');
        }

        return (int) $id;
    }

    public function list(array $filters = []): array
    {
        $model = $this->newModel();

        if (! empty($filters['module'])) {
            $model->where('module', (string) $filters['module']);
        }

        if (! empty($filters['action'])) {
            $model->where('action', (string) $filters['action']);
        }

        if (! empty($filters['reference_type'])) {
            $model->where('reference_type', (string) $filters['reference_type']);
        }

        if (! empty($filters['reference_id'])) {
            $model->where('reference_id', (int) $filters['reference_id']);
        }

        return $model->orderBy('id', 'DESC')->findAll();
    }

    private function newModel(): AuditLogModel
    {
        return new AuditLogModel();
    }
}
