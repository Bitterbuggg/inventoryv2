<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowNullReferenceIdOnStockMovements extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('stock_movements')) {
            return;
        }

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
        if (! $this->db->tableExists('stock_movements')) {
            return;
        }

        $this->db->table('stock_movements')
            ->where('reference_id', null)
            ->set(['reference_id' => 0])
            ->update();

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
