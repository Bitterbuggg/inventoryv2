<?php

namespace App\Models\Receiving;

use CodeIgniter\Model;

class ReceivingModel extends Model
{
    protected $table = 'receivings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'receiving_number',
        'po_request_id',
        'purchase_order_id',
        'supplier_name',
        'received_date',
        'delivery_reference',
        'received_by',
        'verified_by',
        'status',
        'remarks',
        'posted_at',
        'voided_at',
        'voided_by',
        'void_reason',
    ];
}
