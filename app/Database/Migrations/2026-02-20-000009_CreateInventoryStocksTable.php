<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryStocksTable extends Migration
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
            'on_hand_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'reserved_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'available_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'default'    => 0,
            ],
            'average_unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'last_movement_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('item_name');
        $this->forge->addKey('expiry_date');
        $this->forge->addUniqueKey(['item_name', 'unit', 'batch_no', 'lot_no', 'expiry_date']);
        $this->forge->createTable('inventory_stocks', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('inventory_stocks', true);
    }
}
