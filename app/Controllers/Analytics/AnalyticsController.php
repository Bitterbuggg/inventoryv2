<?php

namespace App\Controllers\Analytics;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class AnalyticsController extends BaseController
{
    public function dashboard(): string|ResponseInterface
    {
        return $this->activityLogs('dashboard');
    }

    public function events(): string|ResponseInterface
    {
        return $this->activityLogs('events');
    }

    public function metrics(): string|ResponseInterface
    {
        return $this->activityLogs('metrics');
    }

    public function activityLogs(?string $legacySource = null): string|ResponseInterface
    {
        $source = $legacySource ?? $this->detectLegacySourceFromPath();
        $filters = $this->resolveActivityLogFilters($source);

        $summary = RepositoryServices::analyticsService()->dashboardSummary($filters['overview_days']);
        $eventRows = RepositoryServices::analyticsService()->listEvents($filters['event_filters'], $filters['event_limit']);
        $trends = RepositoryServices::analyticsService()->eventTrendsByDate($filters['metric_date_from'], $filters['metric_date_to']);
        $metrics = RepositoryServices::analyticsService()->listDailyMetrics([
            'date_from' => $filters['metric_date_from'],
            'date_to'   => $filters['metric_date_to'],
            'module'    => $filters['metric_module'],
        ], 500);

        $dataset = $this->resolveExportDataset($source);
        if ($dataset !== null) {
            return $this->exportDataset($dataset, $summary, $eventRows, $trends, $metrics);
        }

        return view('analytics/activity_logs', [
            'summary' => $summary,
            'overview_days' => $filters['overview_days'],
            'recent_total' => count($summary['recent_events'] ?? []),
            'event_rows' => $eventRows,
            'event_filters' => $filters['event_filters'],
            'event_limit' => $filters['event_limit'],
            'event_total' => count($eventRows),
            'metric_date_from' => $filters['metric_date_from'],
            'metric_date_to' => $filters['metric_date_to'],
            'metric_module' => $filters['metric_module'],
            'trends' => $trends,
            'metrics' => $metrics,
            'legacy_source' => $source,
        ]);
    }

    public function track(): ResponseInterface
    {
        $rules = [
            'event_name' => 'required|min_length[3]|max_length[150]',
            'module'     => 'required|min_length[2]|max_length[80]',
            'reference_type' => 'permit_empty|max_length[80]',
            'reference_id' => 'permit_empty|integer',
            'metadata_json' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $metadata = [];
        $metadataRaw = $this->request->getPost('metadata_json');

        if (is_string($metadataRaw) && trim($metadataRaw) !== '') {
            $decoded = json_decode($metadataRaw, true);
            if (is_array($decoded)) {
                $metadata = $decoded;
            }
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            (string) $this->request->getPost('event_name'),
            (string) $this->request->getPost('module'),
            $this->request->getPost('reference_type') !== null ? (string) $this->request->getPost('reference_type') : null,
            $this->request->getPost('reference_id') !== null ? (int) $this->request->getPost('reference_id') : null,
            $metadata,
        );

        return $this->response->setJSON(['status' => 'ok']);
    }

    /**
     * @return array{
     *     overview_days:int,
     *     event_filters:array<string,string>,
     *     event_limit:int,
     *     metric_date_from:string,
     *     metric_date_to:string,
     *     metric_module:string
     * }
     */
    private function resolveActivityLogFilters(?string $source): array
    {
        $overviewDays = (int) ($this->request->getGet('overview_days') ?? $this->request->getGet('days') ?? 7);
        $overviewDays = max(1, min(30, $overviewDays));

        $legacyEventModule = $source === 'events' ? trim((string) $this->request->getGet('module')) : '';
        $legacyEventDateFrom = $source === 'events' ? trim((string) $this->request->getGet('date_from')) : '';
        $legacyEventDateTo = $source === 'events' ? trim((string) $this->request->getGet('date_to')) : '';

        $eventFilters = [
            'event_name' => trim((string) $this->request->getGet('event_name')),
            'module'     => trim((string) ($this->request->getGet('event_module') ?? $legacyEventModule)),
            'actor_id'   => trim((string) ($this->request->getGet('event_actor_id') ?? $this->request->getGet('actor_id'))),
            'date_from'  => trim((string) ($this->request->getGet('event_date_from') ?? $legacyEventDateFrom)),
            'date_to'    => trim((string) ($this->request->getGet('event_date_to') ?? $legacyEventDateTo)),
        ];

        $eventLimitRaw = $this->request->getGet('event_limit');
        if ($eventLimitRaw === null || $eventLimitRaw === '') {
            $eventLimitRaw = $this->request->getGet('limit');
        }
        $eventLimit = (int) ($eventLimitRaw ?? 500);
        $eventLimit = max(1, min(1000, $eventLimit));

        $legacyMetricModule = $source === 'metrics' ? trim((string) $this->request->getGet('module')) : '';
        $legacyMetricDateFrom = $source === 'metrics' ? trim((string) $this->request->getGet('date_from')) : '';
        $legacyMetricDateTo = $source === 'metrics' ? trim((string) $this->request->getGet('date_to')) : '';

        $metricDateFromRaw = trim((string) ($this->request->getGet('metric_date_from') ?? $legacyMetricDateFrom));
        $metricDateToRaw = trim((string) ($this->request->getGet('metric_date_to') ?? $legacyMetricDateTo));

        $metricDateFrom = $metricDateFromRaw !== '' ? $metricDateFromRaw : date('Y-m-d', strtotime('-6 days'));
        $metricDateTo = $metricDateToRaw !== '' ? $metricDateToRaw : date('Y-m-d');
        $metricModule = trim((string) ($this->request->getGet('metric_module') ?? $legacyMetricModule));

        return [
            'overview_days' => $overviewDays,
            'event_filters' => $eventFilters,
            'event_limit' => $eventLimit,
            'metric_date_from' => $metricDateFrom,
            'metric_date_to' => $metricDateTo,
            'metric_module' => $metricModule,
        ];
    }

    private function resolveExportDataset(?string $source): ?string
    {
        if (! $this->shouldExportCsv()) {
            return null;
        }

        $dataset = strtolower(trim((string) $this->request->getGet('dataset')));
        if ($dataset === 'dashboard') {
            $dataset = 'overview';
        }

        if ($dataset !== '') {
            return $dataset;
        }

        return match ($source) {
            'dashboard' => 'overview',
            'events' => 'events',
            'metrics' => 'trends',
            default => 'overview',
        };
    }

    /**
     * @param array<string,mixed> $summary
     * @param array<int,array<string,mixed>> $eventRows
     * @param array<int,array<string,mixed>> $trends
     * @param array<int,array<string,mixed>> $metrics
     */
    private function exportDataset(string $dataset, array $summary, array $eventRows, array $trends, array $metrics): ResponseInterface
    {
        if ($dataset === 'events') {
            return $this->csvResponse(
                'analytics_events_' . date('Ymd_His') . '.csv',
                ['ID', 'Event', 'Module', 'Actor ID', 'Reference Type', 'Reference ID', 'Route', 'Method', 'Metadata', 'Created At'],
                array_map(static fn (array $row): array => [
                    (string) ($row['id'] ?? ''),
                    (string) ($row['event_name'] ?? ''),
                    (string) ($row['module'] ?? ''),
                    (string) ($row['actor_id'] ?? ''),
                    (string) ($row['reference_type'] ?? ''),
                    (string) ($row['reference_id'] ?? ''),
                    (string) ($row['route'] ?? ''),
                    (string) ($row['method'] ?? ''),
                    (string) ($row['metadata_json'] ?? ''),
                    (string) ($row['created_at'] ?? ''),
                ], $eventRows),
            );
        }

        if (in_array($dataset, ['metrics', 'daily_metrics'], true)) {
            return $this->csvResponse(
                'analytics_daily_metrics_' . date('Ymd_His') . '.csv',
                ['Date', 'Metric Key', 'Module', 'Value', 'Dimensions', 'Created At'],
                array_map(static fn (array $row): array => [
                    (string) ($row['metric_date'] ?? ''),
                    (string) ($row['metric_key'] ?? ''),
                    (string) ($row['module'] ?? ''),
                    (string) ($row['metric_value'] ?? '0'),
                    (string) ($row['dimension_json'] ?? ''),
                    (string) ($row['created_at'] ?? ''),
                ], $metrics),
            );
        }

        if ($dataset === 'trends') {
            return $this->csvResponse(
                'analytics_trends_' . date('Ymd_His') . '.csv',
                ['Date', 'Module', 'Total Events'],
                array_map(static fn (array $row): array => [
                    (string) ($row['metric_date'] ?? ''),
                    (string) ($row['module'] ?? ''),
                    (string) ($row['total'] ?? '0'),
                ], $trends),
            );
        }

        $recentEvents = $summary['recent_events'] ?? [];

        return $this->csvResponse(
            'analytics_dashboard_recent_events_' . date('Ymd_His') . '.csv',
            ['ID', 'Event', 'Module', 'Actor ID', 'Reference Type', 'Reference ID', 'Route', 'Method', 'Created At'],
            array_map(static fn (array $row): array => [
                (string) ($row['id'] ?? ''),
                (string) ($row['event_name'] ?? ''),
                (string) ($row['module'] ?? ''),
                (string) ($row['actor_id'] ?? ''),
                (string) ($row['reference_type'] ?? ''),
                (string) ($row['reference_id'] ?? ''),
                (string) ($row['route'] ?? ''),
                (string) ($row['method'] ?? ''),
                (string) ($row['created_at'] ?? ''),
            ], $recentEvents),
        );
    }

    private function detectLegacySourceFromPath(): ?string
    {
        $path = trim(str_replace('index.php/', '', (string) uri_string()), '/');

        return match ($path) {
            'analytics/dashboard' => 'dashboard',
            'analytics/events' => 'events',
            'analytics/metrics' => 'metrics',
            default => null,
        };
    }
}
