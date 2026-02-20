<?php

namespace App\Models\Receiving;

use CodeIgniter\Model;

class ReceivingItemModel extends Model
{
    protected $table = 'receiving_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'receiving_id',
        'purchase_order_item_id',
        'item_name',
        'unit',
        'received_qty',
        'accepted_qty',
        'rejected_qty',
        'batch_no',
        'lot_no',
        'expiry_date',
        'unit_cost',
        'line_total',
        'remarks',
    ];
}
