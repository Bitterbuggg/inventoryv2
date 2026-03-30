<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;

class IssuanceItemModel extends Model
{
    protected $table = 'issuance_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'issuance_id',
        'product_id',
        'item_name',
        'unit',
        'inventory_stock_id',
        'requested_qty',
        'issued_qty',
        'unit_cost',
        'line_total',
        'remarks',
    ];
}
