<?php

namespace App\Models\Procurement;

use CodeIgniter\Model;

class PoRequestModel extends Model
{
    protected $table = 'po_requests';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'po_request_number',
        'purchase_order_id',
        'requested_by',
        'request_date',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
    ];
}