<?php

namespace App\Services\Analytics;

use App\Repositories\Contracts\Analytics\AnalyticsRepositoryInterface;
use Config\Analytics;
use function auth;

class AnalyticsService
{
    public function __construct(private readonly AnalyticsRepositoryInterface $events)
    {
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function track(
        string $eventName,
        string $module,
        ?int $actorId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = [],
        ?string $route = null,
        ?string $method = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): void {
        try {
            $this->events->createEvent([
                'event_name'     => $this->truncate($eventName, 150),
                'module'         => $this->truncate($module, 80),
                'actor_id'       => $actorId,
                'reference_type' => $this->nullableString($referenceType, 80),
                'reference_id'   => $referenceId,
                'route'          => $this->nullableString($route, 255),
                'method'         => $this->nullableString($method, 16),
                'ip_address'     => $this->maskIpAddress($this->nullableString($ipAddress, 45)),
                'user_agent'     => $this->nullableString($userAgent),
                'metadata_json'  => $metadata === [] ? null : json_encode($metadata),
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $exception) {
            log_message('warning', 'Analytics tracking failed: {message}', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function trackHttp(string $eventName, string $module, ?int $actorId = null, ?string $referenceType = null, ?int $referenceId = null, array $metadata = []): void
    {
        $request = service('request');

        $this->track(
            $eventName,
            $module,
            $actorId,
            $referenceType,
            $referenceId,
            $metadata,
            $this->requestPath(),
            strtoupper((string) $request->getMethod()),
            $request->getIPAddress(),
            (string) $request->getUserAgent(),
        );
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function trackCurrentUser(string $eventName, string $module, ?string $referenceType = null, ?int $referenceId = null, array $metadata = []): void
    {
        $user = function_exists('auth') ? auth()->user() : null;
        $actorId = $user === null ? null : (int) ($user->id ?? 0);

        $this->trackHttp($eventName, $module, $actorId, $referenceType, $referenceId, $metadata);
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function listEvents(array $filters = [], int $limit = 200): array
    {
        return $this->events->listEvents($filters, $limit);
    }

    /**
     * @return array<string, mixed>
     */
    public function dashboardSummary(int $days = 7): array
    {
        $days = max(1, $days);
        $dateFrom = date('Y-m-d H:i:s', strtotime('-' . ($days - 1) . ' days midnight'));
        $today = date('Y-m-d 00:00:00');

        return [
            'summary' => [
                'total_events'       => $this->events->countAllEvents(),
                'events_today'       => $this->events->countEventsSince($today),
                'events_last_period' => $this->events->countEventsSince($dateFrom),
                'period_days'        => $days,
            ],
            'module_totals' => $this->events->countByModuleSince($dateFrom),
            'top_events'    => $this->events->topEventNamesSince($dateFrom, 10),
            'top_routes'    => $this->events->topRoutesSince($dateFrom, 10),
            'recent_events' => $this->events->listEvents([], 20),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function eventTrendsByDate(string $dateFrom, string $dateTo): array
    {
        return $this->events->eventTrendsByDate($dateFrom, $dateTo);
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function listDailyMetrics(array $filters = [], int $limit = 365): array
    {
        return $this->events->listDailyMetrics($filters, $limit);
    }

    /**
     * @return array<string, int>
     */
    public function aggregateDailyMetricsForDate(string $date): array
    {
        $this->events->deleteDailyMetricsForDate($date);

        $eventRows = $this->events->aggregateEventCountsForDate($date);
        $moduleRows = $this->events->aggregateModuleCountsForDate($date);

        $insertedEventMetrics = 0;
        $insertedModuleMetrics = 0;

        foreach ($eventRows as $row) {
            $eventName = (string) ($row['event_name'] ?? 'unknown');
            $module = (string) ($row['module'] ?? 'unknown');
            $total = (int) ($row['total'] ?? 0);

            $this->events->createDailyMetric([
                'metric_date'    => $date,
                'metric_key'     => 'event.count.' . $this->metricKey($eventName),
                'metric_value'   => $total,
                'module'         => $module,
                'dimension_json' => json_encode(['event_name' => $eventName]),
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            $insertedEventMetrics++;
        }

        foreach ($moduleRows as $row) {
            $module = (string) ($row['module'] ?? 'unknown');
            $total = (int) ($row['total'] ?? 0);

            $this->events->createDailyMetric([
                'metric_date'    => $date,
                'metric_key'     => 'module.total_events',
                'metric_value'   => $total,
                'module'         => $module,
                'dimension_json' => null,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            $insertedModuleMetrics++;
        }

        return [
            'event_metrics'  => $insertedEventMetrics,
            'module_metrics' => $insertedModuleMetrics,
            'total_metrics'  => $insertedEventMetrics + $insertedModuleMetrics,
        ];
    }

    /**
     * @return array<string, int>
     */
    public function pruneRetentionData(?int $rawEventDays = null, ?int $metricDays = null): array
    {
        $config = $this->config();

        $rawDays = $rawEventDays ?? $config->rawEventRetentionDays;
        $dailyDays = $metricDays ?? $config->dailyMetricRetentionDays;

        $rawDays = max(1, $rawDays);
        $dailyDays = max(1, $dailyDays);

        $eventCutoff = date('Y-m-d H:i:s', strtotime('-' . $rawDays . ' days'));
        $metricCutoff = date('Y-m-d', strtotime('-' . $dailyDays . ' days'));

        return [
            'events_deleted' => $this->events->deleteEventsOlderThan($eventCutoff),
            'metrics_deleted' => $this->events->deleteDailyMetricsOlderThan($metricCutoff),
        ];
    }

    private function requestPath(): string
    {
        $request = service('request');
        $path = trim((string) $request->getPath(), '/');

        if ($path === '') {
            return '/';
        }

        return '/' . $path;
    }

    private function truncate(string $value, int $length): string
    {
        return mb_substr(trim($value), 0, $length);
    }

    private function nullableString(?string $value, int $length = 65535): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim($value);

        if ($text === '') {
            return null;
        }

        return mb_substr($text, 0, $length);
    }

    private function maskIpAddress(?string $ipAddress): ?string
    {
        if ($ipAddress === null) {
            return null;
        }

        $config = $this->config();

        if (! $config->maskIpAddress) {
            return $ipAddress;
        }

        if ($config->ipMaskStrategy === 'truncate') {
            return $this->truncateIpAddress($ipAddress);
        }

        // Default: deterministic hash to avoid storing raw IPs.
        return 'h:' . substr(sha1($ipAddress), 0, 40);
    }

    private function truncateIpAddress(string $ipAddress): string
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ipAddress);
            $parts[3] = '0';

            return implode('.', $parts);
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ipAddress);
            $parts = array_slice($parts, 0, 4);

            return rtrim(implode(':', $parts), ':') . '::';
        }

        return $ipAddress;
    }

    private function metricKey(string $raw): string
    {
        $normalized = strtolower(trim($raw));
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized) ?? 'unknown';
        $normalized = trim($normalized, '_');

        return $normalized === '' ? 'unknown' : $normalized;
    }

    private function config(): Analytics
    {
        /** @var Analytics $config */
        $config = config('Analytics');

        return $config;
    }
}
