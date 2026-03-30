<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;

class IssuanceItemAllocationModel extends Model
{
    protected $table = 'issuance_item_allocations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'issuance_id',
        'issuance_item_id',
        'inventory_stock_id',
        'product_id',
        'item_name',
        'unit',
        'batch_no',
        'lot_no',
        'expiry_date',
        'qty_issued',
        'unit_cost',
        'line_total',
    ];
}
