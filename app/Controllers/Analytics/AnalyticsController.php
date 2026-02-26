<?php

namespace App\Controllers\Analytics;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class AnalyticsController extends BaseController
{
    public function dashboard(): string|ResponseInterface
    {
        $days = (int) ($this->request->getGet('days') ?? 7);
        $clampedDays = max(1, min(30, $days));
        $summary = RepositoryServices::analyticsService()->dashboardSummary($clampedDays);

        if ($this->shouldExportCsv()) {
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

        // Pass everything directly to the view for instant JS pagination
        return view('analytics/dashboard', [
            'days'         => $clampedDays,
            'total_events' => count($summary['recent_events'] ?? [])
        ] + $summary);
    }

    public function events(): string|ResponseInterface
    {
        $filters = [
            'event_name' => trim((string) $this->request->getGet('event_name')),
            'module'     => trim((string) $this->request->getGet('module')),
            'actor_id'   => trim((string) $this->request->getGet('actor_id')),
            'date_from'  => trim((string) $this->request->getGet('date_from')),
            'date_to'    => trim((string) $this->request->getGet('date_to')),
        ];

        // Fetch all matching records up to the limit
        $limit = (int) ($this->request->getGet('limit') ?? 500);
        $allEvents = RepositoryServices::analyticsService()->listEvents($filters, max(1, min(1000, $limit)));

        if ($this->shouldExportCsv()) {
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
                ], $allEvents),
            );
        }

        return view('analytics/events', [
            'rows'         => $allEvents, // Pass all rows to the browser for instant JS pagination
            'filters'      => $filters,
            'limit'        => $limit,
            'total_events' => count($allEvents)
        ]);
    }

    public function metrics(): string|ResponseInterface
    {
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo = trim((string) $this->request->getGet('date_to'));
        $module = trim((string) $this->request->getGet('module'));
        $dataset = trim((string) $this->request->getGet('dataset'));

        $from = $dateFrom !== '' ? $dateFrom : date('Y-m-d', strtotime('-6 days'));
        $to = $dateTo !== '' ? $dateTo : date('Y-m-d');

        $trends = RepositoryServices::analyticsService()->eventTrendsByDate($from, $to);
        $metrics = RepositoryServices::analyticsService()->listDailyMetrics([
            'date_from' => $from,
            'date_to'   => $to,
            'module'    => $module,
        ], 500);

        if ($this->shouldExportCsv()) {
            if ($dataset === 'metrics') {
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

        return view('analytics/metrics', [
            'date_from' => $from,
            'date_to'   => $to,
            'module'    => $module,
            'trends'    => $trends,
            'metrics'   => $metrics,
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
}
