<?php

use App\Services\Analytics\AnalyticsExportPresenter;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AnalyticsExportPresenterTest extends CIUnitTestCase
{
    public function testExportDatasetBuildsEventsCsv(): void
    {
        $presenter = new AnalyticsExportPresenter();

        $csv = $presenter->exportDataset('events', [
            'summary' => [],
            'event_rows' => [[
                'id' => 7,
                'event_name' => 'report.viewed',
                'module' => 'reports',
                'actor_id' => 2,
                'reference_type' => 'report',
                'reference_id' => null,
                'route' => '/reports/stock-balance',
                'method' => 'GET',
                'metadata_json' => '{"report":"stock_balance"}',
                'created_at' => '2026-03-30 10:00:00',
            ]],
            'trends' => [],
            'metrics' => [],
        ]);

        $this->assertSame('ID', $csv['headers'][0]);
        $this->assertSame('report.viewed', $csv['rows'][0][1]);
        $this->assertSame('/reports/stock-balance', $csv['rows'][0][6]);
    }

    public function testExportDatasetBuildsOverviewCsvFromRecentEvents(): void
    {
        $presenter = new AnalyticsExportPresenter();

        $csv = $presenter->exportDataset('overview', [
            'summary' => [
                'recent_events' => [[
                    'id' => 11,
                    'event_name' => 'auth.login_success',
                    'module' => 'auth',
                    'actor_id' => 1,
                    'reference_type' => '',
                    'reference_id' => '',
                    'route' => '/login',
                    'method' => 'POST',
                    'created_at' => '2026-03-30 09:00:00',
                ]],
            ],
            'event_rows' => [],
            'trends' => [],
            'metrics' => [],
        ]);

        $this->assertSame('Event', $csv['headers'][1]);
        $this->assertSame('auth.login_success', $csv['rows'][0][1]);
        $this->assertSame('/login', $csv['rows'][0][6]);
    }

    public function testExportDatasetBuildsTrendsCsv(): void
    {
        $presenter = new AnalyticsExportPresenter();

        $csv = $presenter->exportDataset('trends', [
            'summary' => [],
            'event_rows' => [],
            'trends' => [[
                'metric_date' => '2026-03-30',
                'module' => 'reports',
                'total' => 14,
            ]],
            'metrics' => [],
        ]);

        $this->assertSame(['Date', 'Module', 'Total Events'], $csv['headers']);
        $this->assertSame('14', $csv['rows'][0][2]);
    }
}
