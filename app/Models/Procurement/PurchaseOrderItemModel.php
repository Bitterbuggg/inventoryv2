<?php

namespace App\Models\Procurement;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'purchase_order_id',
        'purchase_request_item_id',
        'product_id',
        'item_name',
        'unit',
        'ordered_qty',
        'received_qty',
        'unit_cost',
        'line_total',
    ];
}
