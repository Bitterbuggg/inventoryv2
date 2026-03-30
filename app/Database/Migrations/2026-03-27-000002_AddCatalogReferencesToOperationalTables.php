<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatalogReferencesToOperationalTables extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('purchase_request_items', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'purchase_request_id',
            ],
        ]);

        $this->forge->addColumn('purchase_orders', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'purchase_request_id',
            ],
        ]);

        $this->forge->addColumn('purchase_order_items', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'purchase_request_item_id',
            ],
        ]);

        $this->forge->addColumn('receivings', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'purchase_order_id',
            ],
        ]);

        $this->forge->addColumn('receiving_items', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'purchase_order_item_id',
            ],
        ]);

        $this->forge->addColumn('inventory_stocks', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addColumn('stock_movements', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'reference_id',
            ],
        ]);

        $this->forge->addColumn('issuance_items', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'issuance_id',
            ],
        ]);

        $this->forge->addColumn('issuance_item_allocations', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'inventory_stock_id',
            ],
        ]);

        $this->backfillProductReferences();
        $this->backfillSupplierReferences();
    }

    public function down(): void
    {
        $this->forge->dropColumn('issuance_item_allocations', 'product_id');
        $this->forge->dropColumn('issuance_items', 'product_id');
        $this->forge->dropColumn('stock_movements', 'product_id');
        $this->forge->dropColumn('inventory_stocks', 'product_id');
        $this->forge->dropColumn('receiving_items', 'product_id');
        $this->forge->dropColumn('receivings', 'supplier_id');
        $this->forge->dropColumn('purchase_order_items', 'product_id');
        $this->forge->dropColumn('purchase_orders', 'supplier_id');
        $this->forge->dropColumn('purchase_request_items', 'product_id');
    }

    private function backfillProductReferences(): void
    {
        $products = [];
        $productRows = $this->db->table('products')
            ->select('id, product_name, unit')
            ->get()
            ->getResultArray();

        foreach ($productRows as $row) {
            $products[$this->productKey(
                (string) ($row['product_name'] ?? ''),
                $this->normalizeUnit($row['unit'] ?? null),
            )] = (int) ($row['id'] ?? 0);
        }

        foreach ([
            'purchase_request_items',
            'purchase_order_items',
            'receiving_items',
            'inventory_stocks',
            'stock_movements',
            'issuance_items',
            'issuance_item_allocations',
        ] as $table) {
            $rows = $this->db->table($table)
                ->select('id, product_id, item_name, unit')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                if ((int) ($row['product_id'] ?? 0) > 0) {
                    continue;
                }

                $productId = $products[$this->productKey(
                    trim((string) ($row['item_name'] ?? '')),
                    $this->normalizeUnit($row['unit'] ?? null),
                )] ?? null;

                if ($productId === null) {
                    continue;
                }

                $this->db->table($table)
                    ->where('id', (int) ($row['id'] ?? 0))
                    ->update(['product_id' => $productId]);
            }
        }
    }

    private function backfillSupplierReferences(): void
    {
        $suppliers = [];
        $supplierRows = $this->db->table('suppliers')
            ->select('id, supplier_name')
            ->get()
            ->getResultArray();

        foreach ($supplierRows as $row) {
            $supplierName = trim((string) ($row['supplier_name'] ?? ''));
            if ($supplierName === '') {
                continue;
            }

            $suppliers[mb_strtolower($supplierName)] = (int) ($row['id'] ?? 0);
        }

        foreach (['purchase_orders', 'receivings'] as $table) {
            $rows = $this->db->table($table)
                ->select('id, supplier_id, supplier_name')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                if ((int) ($row['supplier_id'] ?? 0) > 0) {
                    continue;
                }

                $supplierName = trim((string) ($row['supplier_name'] ?? ''));
                if ($supplierName === '') {
                    continue;
                }

                $supplierId = $suppliers[mb_strtolower($supplierName)] ?? null;

                if ($supplierId === null) {
                    continue;
                }

                $this->db->table($table)
                    ->where('id', (int) ($row['id'] ?? 0))
                    ->update(['supplier_id' => $supplierId]);
            }
        }
    }

    private function productKey(string $productName, string $unit): string
    {
        return mb_strtolower($productName) . '|' . mb_strtolower($unit);
    }

    private function normalizeUnit(mixed $value): string
    {
        $unit = trim((string) $value);

        return $unit === '' ? 'unit' : $unit;
    }
}
