<?php

namespace App\Models\Catalog;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'supplier_code',
        'supplier_name',
        'contact_person',
        'phone',
        'email',
        'is_active',
    ];
}
