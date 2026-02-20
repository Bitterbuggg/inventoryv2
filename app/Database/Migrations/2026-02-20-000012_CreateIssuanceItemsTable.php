<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIssuanceItemsTable extends Migration
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
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unit',
            ],
            'inventory_stock_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'requested_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
            ],
            'issued_qty' => [
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
        $this->forge->addKey('issuance_id');
        $this->forge->addKey('item_name');
        $this->forge->addKey('inventory_stock_id');
        $this->forge->addForeignKey('issuance_id', 'issuances', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_stock_id', 'inventory_stocks', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('issuance_items', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('issuance_items', true);
    }
}
