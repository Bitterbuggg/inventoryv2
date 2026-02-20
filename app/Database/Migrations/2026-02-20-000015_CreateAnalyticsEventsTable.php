<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnalyticsEventsTable extends Migration
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
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
            ],
            'actor_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'reference_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'route' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metadata_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_name');
        $this->forge->addKey('module');
        $this->forge->addKey('actor_id');
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->addKey('created_at');
        $this->forge->createTable('analytics_events', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('analytics_events', true);
    }
}
