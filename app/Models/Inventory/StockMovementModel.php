<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'movement_number',
        'movement_type',
        'reference_type',
        'reference_id',
        'item_name',
        'inventory_stock_id',
        'unit',
        'qty_in',
        'qty_out',
        'balance_after',
        'unit_cost',
        'performed_by',
        'performed_at',
        'remarks',
    ];
}
