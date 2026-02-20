<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReceivingsTable extends Migration
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
            'receiving_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'po_request_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'purchase_order_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'supplier_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'received_date' => [
                'type' => 'DATE',
            ],
            'delivery_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'received_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'verified_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'draft',
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'posted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'voided_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'voided_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'void_reason' => [
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
        $this->forge->addUniqueKey('receiving_number');
        $this->forge->addKey('status');
        $this->forge->addKey('po_request_id');
        $this->forge->addKey('purchase_order_id');
        $this->forge->addForeignKey('po_request_id', 'po_requests', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('receivings', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('receivings', true);
    }
}
