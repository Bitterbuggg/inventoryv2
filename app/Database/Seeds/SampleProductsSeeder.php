<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $rows = [];
        $productNames = $this->buildProductNames(120);

        foreach ($productNames as $index => $itemName) {
            $unit = $this->resolveUnitFromName($itemName);
            $onHandQty = (float) (20 + ($index % 85));
            $reservedQty = (float) (($index % 7 === 0) ? 2 : 0);
            $availableQty = $onHandQty - $reservedQty;
            $averageUnitCost = round(5 + (($index * 1.37) % 145), 2);

            $rows[] = [
                'item_name'         => $itemName,
                'unit'              => $unit,
                'batch_no'          => sprintf('BATCH-%04d', $index + 1),
                'lot_no'            => sprintf('LOT-%04d', $index + 1),
                'expiry_date'       => date('Y-m-d', strtotime('+' . (6 + ($index % 30)) . ' months')),
                'on_hand_qty'       => $onHandQty,
                'reserved_qty'      => $reservedQty,
                'available_qty'     => $availableQty,
                'average_unit_cost' => $averageUnitCost,
                'last_movement_at'  => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        // Use INSERT IGNORE/OR IGNORE behavior so reruns do not fail on unique keys.
        $this->db->table('inventory_stocks')->ignore(true)->insertBatch($rows);
    }

    /**
     * @return array<int, string>
     */
    private function buildProductNames(int $target): array
    {
        $bases = [
            'Paracetamol',
            'Amoxicillin',
            'Cefalexin',
            'Azithromycin',
            'Ibuprofen',
            'Mefenamic Acid',
            'Diclofenac',
            'Metformin',
            'Losartan',
            'Amlodipine',
            'Atorvastatin',
            'Omeprazole',
            'Pantoprazole',
            'Cetirizine',
            'Loratadine',
            'Salbutamol',
            'Montelukast',
            'Multivitamins',
            'Ascorbic Acid',
            'Calcium Carbonate',
            'Ferrous Sulfate',
            'Folic Acid',
            'Zinc Sulfate',
            'Co-Amoxiclav',
            'Ciprofloxacin',
            'Levofloxacin',
            'Clindamycin',
            'Doxycycline',
            'Hydrocortisone',
            'Betamethasone',
        ];

        $strengths = [
            '5mg',
            '10mg',
            '20mg',
            '25mg',
            '50mg',
            '100mg',
            '250mg',
            '500mg',
            '650mg',
            '1g',
            '125mg/5ml',
            '250mg/5ml',
            '100mg/5ml',
        ];

        $forms = [
            'Tablet',
            'Capsule',
            'Syrup',
            'Suspension',
            'Injection',
            'Cream',
            'Ointment',
            'Drops',
        ];

        $names = [];

        foreach ($bases as $base) {
            foreach ($strengths as $strength) {
                foreach ($forms as $form) {
                    $names[] = trim($base . ' ' . $strength . ' ' . $form);

                    if (count($names) >= $target) {
                        return $names;
                    }
                }
            }
        }

        return $names;
    }

    private function resolveUnitFromName(string $itemName): string
    {
        $name = strtolower($itemName);

        if (str_contains($name, 'tablet') || str_contains($name, 'capsule')) {
            return 'box';
        }

        if (str_contains($name, 'syrup') || str_contains($name, 'suspension') || str_contains($name, 'drops')) {
            return 'bottle';
        }

        if (str_contains($name, 'injection')) {
            return 'vial';
        }

        if (str_contains($name, 'cream') || str_contains($name, 'ointment')) {
            return 'tube';
        }

        return 'unit';
    }
}
