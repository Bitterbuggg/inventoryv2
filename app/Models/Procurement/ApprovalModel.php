<?php

namespace App\Models\Procurement;

use CodeIgniter\Model;

class ApprovalModel extends Model
{
    protected $table = 'approvals';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'reference_type',
        'reference_id',
        'approval_level',
        'approver_id',
        'decision',
        'decision_at',
        'comments',
    ];
}