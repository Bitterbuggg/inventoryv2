<?php

namespace App\Services\Analytics;

class ActivityLogQueryService
{
    public function __construct(private readonly AnalyticsService $analytics)
    {
    }

    /**
     * @param array{
     *     overview_days:int,
     *     event_filters:array<string,string>,
     *     event_limit:int,
     *     metric_date_from:string,
     *     metric_date_to:string,
     *     metric_module:string
     * } $filters
     *
     * @return array<string, mixed>
     */
    public function buildActivityLogViewData(array $filters, ?string $legacySource = null): array
    {
        $summary = $this->analytics->dashboardSummary((int) $filters['overview_days']);
        $eventRows = $this->analytics->listEvents($filters['event_filters'], (int) $filters['event_limit']);
        $trends = $this->analytics->eventTrendsByDate(
            (string) $filters['metric_date_from'],
            (string) $filters['metric_date_to'],
        );
        $metrics = $this->analytics->listDailyMetrics([
            'date_from' => $filters['metric_date_from'],
            'date_to'   => $filters['metric_date_to'],
            'module'    => $filters['metric_module'],
        ], 500);

        return [
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
            'legacy_source' => $legacySource,
        ];
    }
}
