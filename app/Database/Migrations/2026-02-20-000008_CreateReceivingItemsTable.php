<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReceivingItemsTable extends Migration
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
            'receiving_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'purchase_order_item_id' => [
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
            'received_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
            ],
            'accepted_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
            ],
            'rejected_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
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
        $this->forge->addKey('receiving_id');
        $this->forge->addKey('purchase_order_item_id');
        $this->forge->addKey('expiry_date');
        $this->forge->addForeignKey('receiving_id', 'receivings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('purchase_order_item_id', 'purchase_order_items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('receiving_items', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('receiving_items', true);
    }
}
