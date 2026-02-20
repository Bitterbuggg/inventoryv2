<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReportingPerformanceIndexes extends Migration
{
    public function up(): void
    {
        $this->forge->addKey('available_qty', false, false, 'idx_inventory_stocks_available_qty');
        $this->forge->processIndexes('inventory_stocks');

        $this->forge->addKey(['movement_type', 'performed_at'], false, false, 'idx_stock_movements_type_date');
        $this->forge->processIndexes('stock_movements');

        $this->forge->addKey(['status', 'issue_date'], false, false, 'idx_issuances_status_date');
        $this->forge->processIndexes('issuances');
    }

    public function down(): void
    {
        $this->forge->dropKey('inventory_stocks', 'idx_inventory_stocks_available_qty', false);
        $this->forge->dropKey('stock_movements', 'idx_stock_movements_type_date', false);
        $this->forge->dropKey('issuances', 'idx_issuances_status_date', false);
    }
}

