<?php

use App\Services\Analytics\ActivityLogQueryService;
use App\Services\Analytics\AnalyticsService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ActivityLogQueryServiceTest extends CIUnitTestCase
{
    public function testBuildActivityLogViewDataReturnsExpectedShape(): void
    {
        $analytics = $this->createMock(AnalyticsService::class);

        $analytics->expects($this->once())
            ->method('dashboardSummary')
            ->with(14)
            ->willReturn([
                'summary' => ['total_events' => 30],
                'recent_events' => [
                    ['id' => 1, 'event_name' => 'auth.login_success'],
                    ['id' => 2, 'event_name' => 'report.viewed'],
                ],
            ]);
        $analytics->expects($this->once())
            ->method('listEvents')
            ->with(['module' => 'reports'], 250)
            ->willReturn([
                ['id' => 10, 'event_name' => 'report.viewed'],
            ]);
        $analytics->expects($this->once())
            ->method('eventTrendsByDate')
            ->with('2026-03-01', '2026-03-30')
            ->willReturn([
                ['metric_date' => '2026-03-30', 'module' => 'reports', 'total' => 4],
            ]);
        $analytics->expects($this->once())
            ->method('listDailyMetrics')
            ->with([
                'date_from' => '2026-03-01',
                'date_to' => '2026-03-30',
                'module' => 'reports',
            ], 500)
            ->willReturn([
                ['metric_date' => '2026-03-30', 'metric_key' => 'module.total_events'],
            ]);

        $service = new ActivityLogQueryService($analytics);

        $result = $service->buildActivityLogViewData([
            'overview_days' => 14,
            'event_filters' => ['module' => 'reports'],
            'event_limit' => 250,
            'metric_date_from' => '2026-03-01',
            'metric_date_to' => '2026-03-30',
            'metric_module' => 'reports',
        ], 'dashboard');

        $this->assertSame(2, $result['recent_total']);
        $this->assertSame(1, $result['event_total']);
        $this->assertSame('dashboard', $result['legacy_source']);
        $this->assertSame('reports', $result['metric_module']);
    }
}
