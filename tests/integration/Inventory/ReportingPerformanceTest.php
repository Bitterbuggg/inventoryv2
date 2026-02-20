<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Inventory\InventoryStockModel;
use App\Models\Inventory\StockMovementModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ReportingPerformanceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testReportsRemainResponsiveWithHighVolumeStockMovements(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $stockId = $this->seedStock('Ibuprofen 200mg', 'box', 5000, 4.25);
        $this->seedHighVolumeMovements($stockId, (int) $admin->id, 1500);

        $movementStart = microtime(true);
        $movementResp  = $this->withSession(session()->get())->get('/reports/stock-movements?date_from=2026-01-01&date_to=2026-12-31&movement_type=issuance');
        $movementMs    = (microtime(true) - $movementStart) * 1000;

        $movementResp->assertOK();
        $movementResp->assertSee('MOV-BULK-');
        $this->assertLessThan(5000, $movementMs, 'Stock movement report should respond within 5s under bulk test load.');

        $fastStart = microtime(true);
        $fastResp  = $this->withSession(session()->get())->get('/reports/fast-moving?date_from=2026-01-01&date_to=2026-12-31&limit=10');
        $fastMs    = (microtime(true) - $fastStart) * 1000;

        $fastResp->assertOK();
        $fastResp->assertSee('Ibuprofen 200mg');
        $this->assertLessThan(5000, $fastMs, 'Fast moving report should respond within 5s under bulk test load.');
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }

    private function seedStock(string $itemName, string $unit, float $qty, float $unitCost): int
    {
        /** @var InventoryStockModel $stockModel */
        $stockModel = model(InventoryStockModel::class);

        $id = $stockModel->insert([
            'item_name'         => $itemName,
            'unit'              => $unit,
            'batch_no'          => 'PERF-BATCH-001',
            'lot_no'            => 'PERF-LOT-001',
            'expiry_date'       => '2027-12-31',
            'on_hand_qty'       => $qty,
            'reserved_qty'      => 0,
            'available_qty'     => $qty,
            'average_unit_cost' => $unitCost,
            'last_movement_at'  => date('Y-m-d H:i:s'),
        ], true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Unable to seed stock for performance test.');
        }

        return (int) $id;
    }

    private function seedHighVolumeMovements(int $stockId, int $performedBy, int $rows): void
    {
        /** @var StockMovementModel $movementModel */
        $movementModel = model(StockMovementModel::class);

        $balance = 5000.0;
        $batch   = [];

        for ($i = 1; $i <= $rows; $i++) {
            $qtyOut  = 1.0;
            $balance -= $qtyOut;

            $batch[] = [
                'movement_number'    => sprintf('MOV-BULK-%05d', $i),
                'movement_type'      => 'issuance',
                'reference_type'     => 'issuance',
                'reference_id'       => 9999,
                'item_name'          => 'Ibuprofen 200mg',
                'inventory_stock_id' => $stockId,
                'unit'               => 'box',
                'qty_in'             => 0,
                'qty_out'            => $qtyOut,
                'balance_after'      => $balance,
                'unit_cost'          => 4.25,
                'performed_by'       => $performedBy,
                'performed_at'       => sprintf('2026-02-%02d 08:00:00', (($i - 1) % 28) + 1),
                'remarks'            => 'Performance test seed',
            ];

            if (count($batch) === 250) {
                $movementModel->insertBatch($batch);
                $batch = [];
            }
        }

        if ($batch !== []) {
            $movementModel->insertBatch($batch);
        }
    }
}
