<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Analytics;
use Config\RepositoryServices;

class AnalyticsPruneCommand extends BaseCommand
{
    protected $group = 'Analytics';
    protected $name = 'analytics:prune';
    protected $description = 'Prune analytics events and metrics based on retention policy.';
    protected $usage = 'analytics:prune [--raw-days N] [--metric-days N]';
    protected $options = [
        '--raw-days' => 'Override raw analytics event retention days.',
        '--metric-days' => 'Override analytics daily metrics retention days.',
    ];

    public function run(array $params): void
    {
        /** @var Analytics $config */
        $config = config('Analytics');

        $rawDays = $this->optionToInt('raw-days', $config->rawEventRetentionDays);
        $metricDays = $this->optionToInt('metric-days', $config->dailyMetricRetentionDays);

        $result = RepositoryServices::analyticsService()->pruneRetentionData($rawDays, $metricDays);

        CLI::write(
            sprintf(
                'Pruned analytics using raw=%d days, metrics=%d days.',
                $rawDays,
                $metricDays,
            ),
            'yellow',
        );
        CLI::write(sprintf('Events deleted: %d', (int) ($result['events_deleted'] ?? 0)), 'green');
        CLI::write(sprintf('Daily metrics deleted: %d', (int) ($result['metrics_deleted'] ?? 0)), 'green');
    }

    private function optionToInt(string $name, int $default): int
    {
        $value = CLI::getOption($name);

        if ($value === null || ctype_digit((string) $value) !== 1) {
            return max(1, $default);
        }

        return max(1, (int) $value);
    }
}
