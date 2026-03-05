<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class NormalizeQuantityCommand extends BaseCommand
{
    protected $group = 'Maintenance';
    protected $name = 'maintenance:normalize-qty';
    protected $description = 'One-time cleanup: normalize decimal quantity values to whole numbers.';
    protected $usage = 'maintenance:normalize-qty [--apply]';
    protected $options = [
        '--apply' => 'Apply updates. Without this flag, command runs in dry-run mode.',
    ];

    /**
     * @var array<int, array{table: string, columns: array<int, string>}>
     */
    private array $targets = [
        ['table' => 'purchase_request_items', 'columns' => ['requested_qty', 'approved_qty']],
        ['table' => 'purchase_order_items', 'columns' => ['ordered_qty', 'received_qty']],
        ['table' => 'receiving_items', 'columns' => ['received_qty', 'accepted_qty', 'rejected_qty']],
        ['table' => 'issuance_items', 'columns' => ['requested_qty', 'issued_qty']],
        ['table' => 'issuance_item_allocations', 'columns' => ['qty_issued']],
        ['table' => 'inventory_stocks', 'columns' => ['on_hand_qty', 'reserved_qty', 'available_qty']],
        ['table' => 'stock_movements', 'columns' => ['qty_in', 'qty_out', 'balance_after']],
    ];

    public function run(array $params): void
    {
        $apply = CLI::getOption('apply') !== null;
        $db = db_connect();

        CLI::write(
            $apply
                ? 'Running quantity normalization in APPLY mode.'
                : 'Running quantity normalization in DRY-RUN mode (no DB changes).',
            $apply ? 'yellow' : 'light_gray',
        );

        $totalCandidates = 0;
        $totalUpdated = 0;

        if ($apply) {
            $db->transBegin();
        }

        foreach ($this->targets as $target) {
            $table = $target['table'];
            $tableSql = $db->protectIdentifiers($table, true);

            CLI::write('');
            CLI::write('Table: ' . $table, 'cyan');

            foreach ($target['columns'] as $column) {
                $columnSql = $db->protectIdentifiers($column);

                $countQuery = sprintf(
                    'SELECT COUNT(*) AS total FROM %s WHERE %s IS NOT NULL AND ABS(%s - ROUND(%s, 0)) > 0.00001',
                    $tableSql,
                    $columnSql,
                    $columnSql,
                    $columnSql,
                );
                $countRow = $db->query($countQuery)->getRowArray();
                $count = (int) ($countRow['total'] ?? 0);

                $totalCandidates += $count;

                if ($count === 0) {
                    CLI::write('  - ' . $column . ': 0 rows', 'light_gray');
                    continue;
                }

                CLI::write('  - ' . $column . ': ' . $count . ' row(s) need normalization', 'yellow');

                if (! $apply) {
                    continue;
                }

                $updateQuery = sprintf(
                    'UPDATE %s SET %s = ROUND(%s, 0) WHERE %s IS NOT NULL AND ABS(%s - ROUND(%s, 0)) > 0.00001',
                    $tableSql,
                    $columnSql,
                    $columnSql,
                    $columnSql,
                    $columnSql,
                    $columnSql,
                );
                $db->query($updateQuery);
                $updated = $db->affectedRows();
                $totalUpdated += $updated;

                CLI::write('    updated: ' . $updated . ' row(s)', 'green');
            }
        }

        CLI::write('');
        CLI::write('Summary:', 'cyan');
        CLI::write('  Candidates found: ' . $totalCandidates, 'yellow');

        if (! $apply) {
            CLI::write('  Updated rows: 0 (dry-run)', 'light_gray');
            CLI::write('');
            CLI::write('To apply updates, run: php spark maintenance:normalize-qty --apply', 'light_gray');
            return;
        }

        if (! $db->transStatus()) {
            $db->transRollback();
            CLI::error('Normalization failed. Transaction rolled back.');
            return;
        }

        $db->transCommit();
        CLI::write('  Updated rows: ' . $totalUpdated, 'green');
        CLI::write('');
        CLI::write('Done.', 'green');
    }
}
