<?php

namespace App\Repositories\Contracts\Analytics;

interface AnalyticsRepositoryInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function createEvent(array $data): int;

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function listEvents(array $filters = [], int $limit = 200): array;

    public function countAllEvents(): int;

    public function countEventsSince(string $dateTime): int;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function countByModuleSince(string $dateTime): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function topEventNamesSince(string $dateTime, int $limit = 10): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function topRoutesSince(string $dateTime, int $limit = 10): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function eventTrendsByDate(string $dateFrom, string $dateTo): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function aggregateEventCountsForDate(string $date): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function aggregateModuleCountsForDate(string $date): array;

    public function deleteEventsOlderThan(string $dateTime): int;

    /**
     * @param array<string, mixed> $data
     */
    public function createDailyMetric(array $data): int;

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function listDailyMetrics(array $filters = [], int $limit = 365): array;

    public function deleteDailyMetricsForDate(string $date): int;

    public function deleteDailyMetricsOlderThan(string $date): int;
}
