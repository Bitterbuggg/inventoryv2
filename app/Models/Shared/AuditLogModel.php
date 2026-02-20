<?php

namespace App\Models\Shared;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'actor_id',
        'action',
        'module',
        'reference_type',
        'reference_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];
}
