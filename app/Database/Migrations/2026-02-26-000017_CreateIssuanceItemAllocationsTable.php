<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIssuanceItemAllocationsTable extends Migration
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
            'issuance_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'issuance_item_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'inventory_stock_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unit',
            ],
            'batch_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'lot_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'qty_issued' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'line_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
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
        $this->forge->addKey('issuance_id');
        $this->forge->addKey('issuance_item_id');
        $this->forge->addKey('inventory_stock_id');
        $this->forge->addKey('expiry_date');
        $this->forge->addForeignKey('issuance_id', 'issuances', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('issuance_item_id', 'issuance_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_stock_id', 'inventory_stocks', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('issuance_item_allocations', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('issuance_item_allocations', true);
    }
}
