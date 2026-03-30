<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCatalogTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unit',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addUniqueKey('product_code');
        $this->forge->addUniqueKey(['product_name', 'unit']);
        $this->forge->addKey('product_name');
        $this->forge->addKey('is_active');
        $this->forge->createTable('products', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'supplier_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'supplier_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'contact_person' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addUniqueKey('supplier_code');
        $this->forge->addUniqueKey('supplier_name');
        $this->forge->addKey('is_active');
        $this->forge->createTable('suppliers', true);

        $this->seedProductsFromOperationalData();
        $this->seedSuppliersFromOperationalData();
    }

    public function down(): void
    {
        $this->forge->dropTable('suppliers', true);
        $this->forge->dropTable('products', true);
    }

    private function seedProductsFromOperationalData(): void
    {
        $catalog = [];

        foreach ($this->productSources() as $source) {
            if (! $this->db->tableExists($source['table'])) {
                continue;
            }

            $rows = $this->db->table($source['table'])
                ->select($source['name_column'] . ', ' . $source['unit_column'])
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $productName = trim((string) ($row[$source['name_column']] ?? ''));
                $unit = $this->normalizeUnit($row[$source['unit_column']] ?? null);

                if ($productName === '') {
                    continue;
                }

                $catalog[$this->productKey($productName, $unit)] = [
                    'product_name' => $productName,
                    'unit'         => $unit,
                ];
            }
        }

        if ($catalog === []) {
            return;
        }

        ksort($catalog);

        $now = date('Y-m-d H:i:s');
        $rows = [];
        $counter = 1;

        foreach ($catalog as $entry) {
            $rows[] = [
                'product_code' => sprintf('PRD-%05d', $counter++),
                'product_name' => $entry['product_name'],
                'unit'         => $entry['unit'],
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        $this->db->table('products')->insertBatch($rows);
    }

    private function seedSuppliersFromOperationalData(): void
    {
        $catalog = [];

        foreach (['purchase_orders', 'receivings'] as $table) {
            if (! $this->db->tableExists($table)) {
                continue;
            }

            $rows = $this->db->table($table)
                ->select('supplier_name')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $supplierName = trim((string) ($row['supplier_name'] ?? ''));
                if ($supplierName === '') {
                    continue;
                }

                $catalog[mb_strtolower($supplierName)] = $supplierName;
            }
        }

        if ($catalog === []) {
            return;
        }

        ksort($catalog);

        $now = date('Y-m-d H:i:s');
        $rows = [];
        $counter = 1;

        foreach ($catalog as $supplierName) {
            $rows[] = [
                'supplier_code'  => sprintf('SUP-%05d', $counter++),
                'supplier_name'  => $supplierName,
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'is_active'      => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        $this->db->table('suppliers')->insertBatch($rows);
    }

    /**
     * @return array<int, array{table: string, name_column: string, unit_column: string}>
     */
    private function productSources(): array
    {
        return [
            ['table' => 'purchase_request_items', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'purchase_order_items', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'receiving_items', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'inventory_stocks', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'stock_movements', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'issuance_items', 'name_column' => 'item_name', 'unit_column' => 'unit'],
            ['table' => 'issuance_item_allocations', 'name_column' => 'item_name', 'unit_column' => 'unit'],
        ];
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
