<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use Config\RepositoryServices;

class ReportingController extends BaseController
{
    public function stockBalance(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));

        $this->trackReportView('stock_balance', [
            'has_keyword' => $keyword !== '',
        ]);

        return view('inventory/reports/stock_balance', [
            'rows'    => RepositoryServices::reportingService()->stockBalance($keyword === '' ? null : $keyword),
            'keyword' => $keyword,
        ]);
    }

    public function stockMovements(): string
    {
        $dateFrom     = trim((string) $this->request->getGet('date_from'));
        $dateTo       = trim((string) $this->request->getGet('date_to'));
        $movementType = trim((string) $this->request->getGet('movement_type'));

        $this->trackReportView('stock_movements', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'movement_type' => $movementType,
        ]);

        return view('inventory/reports/stock_movements', [
            'rows'         => RepositoryServices::reportingService()->stockMovements(
                $dateFrom === '' ? null : $dateFrom,
                $dateTo === '' ? null : $dateTo,
                $movementType === '' ? null : $movementType,
            ),
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'movement_type' => $movementType,
        ]);
    }

    public function issuances(): string
    {
        $status   = trim((string) $this->request->getGet('status'));
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));

        $this->trackReportView('issuances', [
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        return view('inventory/reports/issuances', [
            'rows'      => RepositoryServices::reportingService()->issuances(
                $status === '' ? null : $status,
                $dateFrom === '' ? null : $dateFrom,
                $dateTo === '' ? null : $dateTo,
            ),
            'status'    => $status,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    public function lowStock(): string
    {
        $threshold = (float) ($this->request->getGet('threshold') ?? 10);

        $this->trackReportView('low_stock', [
            'threshold' => $threshold,
        ]);

        return view('inventory/reports/low_stock', [
            'rows'       => RepositoryServices::reportingService()->lowStock($threshold),
            'threshold'  => $threshold,
        ]);
    }

    public function fastMoving(): string
    {
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));
        $limit    = (int) ($this->request->getGet('limit') ?? 20);

        $this->trackReportView('fast_moving', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
        ]);

        return view('inventory/reports/fast_moving', [
            'rows'      => RepositoryServices::reportingService()->fastMoving(
                $dateFrom === '' ? null : $dateFrom,
                $dateTo === '' ? null : $dateTo,
                $limit,
            ),
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'limit'     => $limit,
        ]);
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function trackReportView(string $reportName, array $filters): void
    {
        RepositoryServices::analyticsService()->trackCurrentUser(
            'report.viewed',
            'reports',
            'report',
            null,
            [
                'report' => $reportName,
                'filters' => $filters,
            ],
        );
    }
}
