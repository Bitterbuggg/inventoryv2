<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnalyticsDailyMetricsTable extends Migration
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
            'metric_date' => [
                'type' => 'DATE',
            ],
            'metric_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'metric_value' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'default'    => 0,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
            ],
            'dimension_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('metric_date');
        $this->forge->addKey('module');
        $this->forge->addKey('metric_key');
        $this->forge->addKey(['metric_date', 'metric_key', 'module']);
        $this->forge->createTable('analytics_daily_metrics', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('analytics_daily_metrics', true);
    }
}
