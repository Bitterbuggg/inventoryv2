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

    public function systemArchitecture(): string
    {
        RepositoryServices::analyticsService()->trackCurrentUser(
            'analytics.system_architecture_viewed',
            'analytics',
        );

        return view('analytics/system_architecture');
    }

    public function activityLogs(?string $legacySource = null): string|ResponseInterface
    {
        $source = $legacySource ?? $this->detectLegacySourceFromPath();
        $filters = $this->resolveActivityLogFilters($source);
        $viewData = RepositoryServices::activityLogQueryService()->buildActivityLogViewData($filters, $source);

        $dataset = $this->resolveExportDataset($source);
        if ($dataset !== null) {
            $csv = RepositoryServices::analyticsExportPresenter()->exportDataset($dataset, $viewData);

            return $this->csvResponse($csv['filename'], $csv['headers'], $csv['rows']);
        }

        return view('analytics/activity_logs', $viewData);
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
