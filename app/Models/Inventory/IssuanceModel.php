<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;

class IssuanceModel extends Model
{
    protected $table = 'issuances';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'issuance_number',
        'requestor_id',
        'issue_date',
        'department',
        'purpose',
        'status',
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'released_by',
        'released_at',
        'remarks',
    ];
}
