<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowNullReferenceIdOnStockMovements extends Migration
{
    public function up(): void
    {
        $this->forge->modifyColumn('stock_movements', [
            'reference_id' => [
                'name'       => 'reference_id',
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down(): void
    {
        $this->db->query('UPDATE stock_movements SET reference_id = 0 WHERE reference_id IS NULL');

        $this->forge->modifyColumn('stock_movements', [
            'reference_id' => [
                'name'       => 'reference_id',
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
