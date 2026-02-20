<?php

namespace App\Models\Procurement;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'po_number',
        'purchase_request_id',
        'supplier_name',
        'order_date',
        'status',
        'subtotal_amount',
        'total_amount',
        'issued_by',
        'issued_at',
    ];
}