<?php

use App\Repositories\Contracts\Analytics\AnalyticsRepositoryInterface;
use App\Services\Analytics\AnalyticsService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AnalyticsServiceTest extends CIUnitTestCase
{
    public function testTrackHandlesRepositoryFailuresGracefully(): void
    {
        $repo = new FakeAnalyticsRepository();
        $repo->throwOnCreateEvent = true;

        $service = new AnalyticsService($repo);
        $service->track('test.event', 'testing');

        $this->assertTrue(true);
    }

    public function testDashboardSummaryReturnsExpectedShape(): void
    {
        $repo = new FakeAnalyticsRepository();
        $repo->countAll = 12;
        $repo->countSince = 5;
        $repo->moduleTotals = [['module' => 'reports', 'total' => 4]];
        $repo->topEvents = [['event_name' => 'report.viewed', 'total' => 4]];
        $repo->topRoutes = [['route' => '/reports/stock-balance', 'total' => 2]];
        $repo->listedEvents = [['id' => 1, 'event_name' => 'a']];

        $service = new AnalyticsService($repo);
        $result = $service->dashboardSummary(7);

        $this->assertArrayHasKey('summary', $result);
        $this->assertArrayHasKey('module_totals', $result);
        $this->assertArrayHasKey('top_events', $result);
        $this->assertArrayHasKey('top_routes', $result);
        $this->assertArrayHasKey('recent_events', $result);
        $this->assertSame(12, $result['summary']['total_events']);
    }

    public function testAggregateDailyMetricsBuildsEventAndModuleMetrics(): void
    {
        $repo = new FakeAnalyticsRepository();
        $repo->aggregateEvents = [
            ['module' => 'auth', 'event_name' => 'auth.login_success', 'total' => 3],
            ['module' => 'procurement', 'event_name' => 'procurement.pr_created', 'total' => 2],
        ];
        $repo->aggregateModules = [
            ['module' => 'auth', 'total' => 3],
            ['module' => 'procurement', 'total' => 2],
        ];

        $service = new AnalyticsService($repo);
        $result = $service->aggregateDailyMetricsForDate('2026-02-20');

        $this->assertSame(2, $result['event_metrics']);
        $this->assertSame(2, $result['module_metrics']);
        $this->assertSame(4, $result['total_metrics']);
        $this->assertCount(4, $repo->dailyMetricsCreated);
        $this->assertSame('event.count.auth_login_success', $repo->dailyMetricsCreated[0]['metric_key']);
    }

    public function testPruneRetentionDataUsesRepositoryResponses(): void
    {
        $repo = new FakeAnalyticsRepository();
        $repo->deletedEvents = 7;
        $repo->deletedMetrics = 4;

        $service = new AnalyticsService($repo);
        $result = $service->pruneRetentionData(30, 60);

        $this->assertSame(7, $result['events_deleted']);
        $this->assertSame(4, $result['metrics_deleted']);
    }

    public function testTrackMasksIpAddressByDefault(): void
    {
        $repo = new FakeAnalyticsRepository();
        $service = new AnalyticsService($repo);

        $service->track('auth.login_success', 'auth', null, null, null, [], '/login', 'POST', '192.168.1.88', 'TestAgent');

        $this->assertCount(1, $repo->eventsCreated);
        $storedIp = (string) $repo->eventsCreated[0]['ip_address'];

        $this->assertStringStartsWith('h:', $storedIp);
        $this->assertNotSame('192.168.1.88', $storedIp);
    }
}

final class FakeAnalyticsRepository implements AnalyticsRepositoryInterface
{
    public bool $throwOnCreateEvent = false;

    /** @var array<int, array<string, mixed>> */
    public array $eventsCreated = [];

    /** @var array<int, array<string, mixed>> */
    public array $dailyMetricsCreated = [];

    /** @var array<int, array<string, mixed>> */
    public array $aggregateEvents = [];

    /** @var array<int, array<string, mixed>> */
    public array $aggregateModules = [];

    /** @var array<int, array<string, mixed>> */
    public array $moduleTotals = [];

    /** @var array<int, array<string, mixed>> */
    public array $topEvents = [];

    /** @var array<int, array<string, mixed>> */
    public array $topRoutes = [];

    /** @var array<int, array<string, mixed>> */
    public array $listedEvents = [];

    public int $countAll = 0;
    public int $countSince = 0;
    public int $deletedEvents = 0;
    public int $deletedMetrics = 0;

    public function createEvent(array $data): int
    {
        if ($this->throwOnCreateEvent) {
            throw new RuntimeException('fail');
        }

        $this->eventsCreated[] = $data;

        return count($this->eventsCreated);
    }

    public function listEvents(array $filters = [], int $limit = 200): array
    {
        return $this->listedEvents;
    }

    public function countAllEvents(): int
    {
        return $this->countAll;
    }

    public function countEventsSince(string $dateTime): int
    {
        return $this->countSince;
    }

    public function countByModuleSince(string $dateTime): array
    {
        return $this->moduleTotals;
    }

    public function topEventNamesSince(string $dateTime, int $limit = 10): array
    {
        return $this->topEvents;
    }

    public function topRoutesSince(string $dateTime, int $limit = 10): array
    {
        return $this->topRoutes;
    }

    public function eventTrendsByDate(string $dateFrom, string $dateTo): array
    {
        return [];
    }

    public function aggregateEventCountsForDate(string $date): array
    {
        return $this->aggregateEvents;
    }

    public function aggregateModuleCountsForDate(string $date): array
    {
        return $this->aggregateModules;
    }

    public function deleteEventsOlderThan(string $dateTime): int
    {
        return $this->deletedEvents;
    }

    public function createDailyMetric(array $data): int
    {
        $this->dailyMetricsCreated[] = $data;

        return count($this->dailyMetricsCreated);
    }

    public function listDailyMetrics(array $filters = [], int $limit = 365): array
    {
        return [];
    }

    public function deleteDailyMetricsForDate(string $date): int
    {
        return 0;
    }

    public function deleteDailyMetricsOlderThan(string $date): int
    {
        return $this->deletedMetrics;
    }
}
