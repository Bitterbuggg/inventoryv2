<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Analytics;
use Config\RepositoryServices;

class AnalyticsAggregateCommand extends BaseCommand
{
    protected $group = 'Analytics';
    protected $name = 'analytics:aggregate';
    protected $description = 'Aggregate analytics events into daily metrics.';
    protected $usage = 'analytics:aggregate [YYYY-MM-DD|--days N]';
    protected $arguments = [
        'date' => 'Optional metric date in YYYY-MM-DD format.',
    ];
    protected $options = [
        '--days' => 'Aggregate the last N days (including today).',
    ];

    public function run(array $params): void
    {
        $dates = $this->resolveDates($params);
        $service = RepositoryServices::analyticsService();

        $totalMetrics = 0;

        foreach ($dates as $date) {
            $result = $service->aggregateDailyMetricsForDate($date);
            $totalMetrics += (int) ($result['total_metrics'] ?? 0);

            CLI::write(
                sprintf(
                    'Aggregated %d metrics for %s (events=%d, modules=%d).',
                    (int) ($result['total_metrics'] ?? 0),
                    $date,
                    (int) ($result['event_metrics'] ?? 0),
                    (int) ($result['module_metrics'] ?? 0),
                ),
                'green',
            );
        }

        CLI::write(sprintf('Done. Total metrics aggregated: %d', $totalMetrics), 'yellow');
    }

    /**
     * @param array<int, string> $params
     *
     * @return array<int, string>
     */
    private function resolveDates(array $params): array
    {
        $daysOption = CLI::getOption('days');

        if ($daysOption !== null && ctype_digit((string) $daysOption)) {
            $days = max(1, (int) $daysOption);
            $dates = [];

            for ($i = 0; $i < $days; $i++) {
                $dates[] = date('Y-m-d', strtotime('-' . $i . ' day'));
            }

            return $dates;
        }

        if (isset($params[0]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $params[0]) === 1) {
            return [$params[0]];
        }

        /** @var Analytics $config */
        $config = config('Analytics');
        $lookback = max(1, $config->defaultAggregationLookbackDays);
        $dates = [];

        for ($i = 0; $i < $lookback; $i++) {
            $dates[] = date('Y-m-d', strtotime('-' . $i . ' day'));
        }

        return $dates;
    }
}
