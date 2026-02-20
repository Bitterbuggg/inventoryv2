<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'movement_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
            ],
            'movement_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'receiving',
            ],
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'reference_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'inventory_stock_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unit',
            ],
            'qty_in' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'qty_out' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'balance_after' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'performed_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'performed_at' => [
                'type' => 'DATETIME',
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('movement_number');
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addKey('item_name');
        $this->forge->addKey('performed_at');
        $this->forge->addForeignKey('inventory_stock_id', 'inventory_stocks', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('stock_movements', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('stock_movements', true);
    }
}
