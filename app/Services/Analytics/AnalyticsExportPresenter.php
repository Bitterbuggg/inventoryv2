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
            return [
                'filename' => 'analytics_events_' . date('Ymd_His') . '.csv',
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
                'filename' => 'analytics_daily_metrics_' . date('Ymd_His') . '.csv',
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
                'filename' => 'analytics_trends_' . date('Ymd_His') . '.csv',
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
            'filename' => 'analytics_dashboard_recent_events_' . date('Ymd_His') . '.csv',
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
}
