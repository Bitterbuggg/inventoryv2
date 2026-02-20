<?php

namespace App\Models\Procurement;

use CodeIgniter\Model;

class PurchaseRequestItemModel extends Model
{
    protected $table = 'purchase_request_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'purchase_request_id',
        'item_name',
        'requested_qty',
        'approved_qty',
        'unit',
        'estimated_unit_cost',
        'notes',
    ];
}