<?php

namespace App\Services\Shared;

use App\Repositories\Contracts\Shared\AuditLogRepositoryInterface;

class AuditService
{
    public function __construct(private readonly AuditLogRepositoryInterface $logs)
    {
    }

    /**
     * @param array<string, mixed>|null $oldValues
     * @param array<string, mixed>|null $newValues
     * @param array<string, mixed> $context
     */
    public function log(
        ?int $actorId,
        string $action,
        string $module,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        array $context = [],
    ): void {
        $this->logs->create([
            'actor_id'      => $actorId,
            'action'        => $action,
            'module'        => $module,
            'reference_type' => $referenceType,
            'reference_id'  => $referenceId,
            'old_values'    => $oldValues === null ? null : json_encode($oldValues),
            'new_values'    => $newValues === null ? null : json_encode($newValues),
            'ip_address'    => $this->nullableString($context['ip_address'] ?? null),
            'user_agent'    => $this->nullableString($context['user_agent'] ?? null),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
