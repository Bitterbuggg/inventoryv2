<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleSuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $rows = [];

        foreach ($this->supplierFixtures() as $index => $supplier) {
            $rows[] = [
                'supplier_code'  => sprintf('SUP-SEED-%04d', $index + 1),
                'supplier_name'  => $supplier['supplier_name'],
                'contact_person' => $supplier['contact_person'],
                'phone'          => $supplier['phone'],
                'email'          => $supplier['email'],
                'is_active'      => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        if ($rows !== []) {
            $this->db->table('suppliers')->ignore(true)->insertBatch($rows);
        }
    }

    /**
     * @return array<int, array{supplier_name:string, contact_person:string, phone:string, email:string}>
     */
    private function supplierFixtures(): array
    {
        return [
            [
                'supplier_name' => 'ACME Pharma Supply',
                'contact_person' => 'Lara Santos',
                'phone' => '09170000001',
                'email' => 'sales@acmepharma.test',
            ],
            [
                'supplier_name' => 'Northwind Medical',
                'contact_person' => 'June Navarro',
                'phone' => '09170000002',
                'email' => 'orders@northwind.test',
            ],
            [
                'supplier_name' => 'Mercury Allied Traders',
                'contact_person' => 'Paolo Reyes',
                'phone' => '09170000003',
                'email' => 'supply@mercuryallied.test',
            ],
            [
                'supplier_name' => 'PrimeCare Distribution',
                'contact_person' => 'Ivy Mendoza',
                'phone' => '09170000004',
                'email' => 'hello@primecare.test',
            ],
            [
                'supplier_name' => 'Sterile Source Inc.',
                'contact_person' => 'Erika Cruz',
                'phone' => '09170000005',
                'email' => 'support@sterilesource.test',
            ],
            [
                'supplier_name' => 'BlueCross Health Depot',
                'contact_person' => 'Nico Valdez',
                'phone' => '09170000006',
                'email' => 'catalog@bluecrossdepot.test',
            ],
            [
                'supplier_name' => 'HealthFirst Wholesale',
                'contact_person' => 'Mika Ramos',
                'phone' => '09170000007',
                'email' => 'trade@healthfirst.test',
            ],
            [
                'supplier_name' => 'VitalMed Logistics',
                'contact_person' => 'Dina Flores',
                'phone' => '09170000008',
                'email' => 'dispatch@vitalmed.test',
            ],
            [
                'supplier_name' => 'SureRx Imports',
                'contact_person' => 'Carl Aquino',
                'phone' => '09170000009',
                'email' => 'inquiries@surerx.test',
            ],
            [
                'supplier_name' => 'Beacon Hospital Supply',
                'contact_person' => 'Anna Lim',
                'phone' => '09170000010',
                'email' => 'team@beaconhospital.test',
            ],
        ];
    }
}
