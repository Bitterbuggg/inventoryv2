<?php

namespace App\Models\Inventory;

use CodeIgniter\Model;

class InventoryStockModel extends Model
{
    protected $table = 'inventory_stocks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'product_id',
        'item_name',
        'unit',
        'batch_no',
        'lot_no',
        'expiry_date',
        'on_hand_qty',
        'reserved_qty',
        'available_qty',
        'average_unit_cost',
        'last_movement_at',
    ];
}
