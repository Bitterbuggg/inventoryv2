<?php

namespace App\Services\Analytics;

class AnalyticsExportPresenter
{
    /**
     * @param array<string, mixed> $viewData
     *
     * @return array{filename: string, headers: array<int, string>, rows: array<int, array<int, string>>}
     */
    public function exportDataset(string $dataset, array $viewData): array
    {
        $summary = is_array($viewData['summary'] ?? null) ? $viewData['summary'] : [];
        $eventRows = is_array($viewData['event_rows'] ?? null) ? $viewData['event_rows'] : [];
        $trends = is_array($viewData['trends'] ?? null) ? $viewData['trends'] : [];
        $metrics = is_array($viewData['metrics'] ?? null) ? $viewData['metrics'] : [];

        if ($dataset === 'events') {
            $eventFilters = is_array($viewData['event_filters'] ?? null) ? $viewData['event_filters'] : [];
            if (($viewData['event_limit'] ?? '') !== '') {
                $eventFilters['event_limit'] = $viewData['event_limit'];
            }

            return [
                'filename' => $this->exportFilename('analytics_events', $eventFilters),
                'headers' => ['ID', 'Event', 'Module', 'Actor ID', 'Reference Type', 'Reference ID', 'Route', 'Method', 'Metadata', 'Created At'],
                'rows' => array_map(static fn (array $row): array => [
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
            ];
        }

        if (in_array($dataset, ['metrics', 'daily_metrics'], true)) {
            return [
                'filename' => $this->exportFilename('analytics_daily_metrics', $this->metricFilters($viewData)),
                'headers' => ['Date', 'Metric Key', 'Module', 'Value', 'Dimensions', 'Created At'],
                'rows' => array_map(static fn (array $row): array => [
                    (string) ($row['metric_date'] ?? ''),
                    (string) ($row['metric_key'] ?? ''),
                    (string) ($row['module'] ?? ''),
                    (string) ($row['metric_value'] ?? '0'),
                    (string) ($row['dimension_json'] ?? ''),
                    (string) ($row['created_at'] ?? ''),
                ], $metrics),
            ];
        }

        if ($dataset === 'trends') {
            return [
                'filename' => $this->exportFilename('analytics_trends', $this->metricFilters($viewData)),
                'headers' => ['Date', 'Module', 'Total Events'],
                'rows' => array_map(static fn (array $row): array => [
                    (string) ($row['metric_date'] ?? ''),
                    (string) ($row['module'] ?? ''),
                    (string) ($row['total'] ?? '0'),
                ], $trends),
            ];
        }

        $recentEvents = is_array($summary['recent_events'] ?? null) ? $summary['recent_events'] : [];

        return [
            'filename' => $this->exportFilename('analytics_dashboard_recent_events', [
                'overview_days' => $viewData['overview_days'] ?? ($summary['summary']['period_days'] ?? ''),
            ]),
            'headers' => ['ID', 'Event', 'Module', 'Actor ID', 'Reference Type', 'Reference ID', 'Route', 'Method', 'Created At'],
            'rows' => array_map(static fn (array $row): array => [
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
        ];
    }

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    private function metricFilters(array $viewData): array
    {
        return [
            'metric_date_from' => $viewData['metric_date_from'] ?? '',
            'metric_date_to' => $viewData['metric_date_to'] ?? '',
            'metric_module' => $viewData['metric_module'] ?? '',
        ];
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function exportFilename(string $baseName, array $filters = []): string
    {
        $parts = [];

        foreach ($filters as $key => $value) {
            $label = $this->filenameSlug($this->filterLabel((string) $key));
            $filterValue = $this->filenameSlug((string) $value);

            if ($label === '' || $filterValue === '' || $filterValue === 'all') {
                continue;
            }

            $parts[] = $label . '-' . $filterValue;
        }

        return $this->filenameSlug($baseName)
            . '_'
            . ($parts === [] ? 'all' : implode('_', $parts))
            . '_'
            . date('Ymd_His')
            . '.csv';
    }

    private function filterLabel(string $key): string
    {
        return [
            'event_name' => 'event',
            'event_module' => 'module',
            'event_actor_id' => 'actor',
            'event_date_from' => 'from',
            'event_date_to' => 'to',
            'event_limit' => 'limit',
            'metric_date_from' => 'from',
            'metric_date_to' => 'to',
            'metric_module' => 'module',
            'actor_id' => 'actor',
            'date_from' => 'from',
            'date_to' => 'to',
            'overview_days' => 'days',
        ][$key] ?? $key;
    }

    private function filenameSlug(string $value): string
    {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($value))) ?? '';

        return trim($slug, '-');
    }
}
