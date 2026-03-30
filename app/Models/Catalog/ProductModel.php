<?php

namespace App\Models\Catalog;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'product_code',
        'product_name',
        'unit',
        'is_active',
    ];
}
