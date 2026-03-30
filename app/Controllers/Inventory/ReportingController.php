<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class ReportingController extends BaseController
{
    public function stockBalance(): string|ResponseInterface
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $rows = RepositoryServices::reportingService()->stockBalance($keyword === '' ? null : $keyword);

        $this->trackReportView('stock_balance', [
            'has_keyword' => $keyword !== '',
        ]);

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::reportingExportPresenter()->stockBalanceCsv($rows);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('inventory/reports/stock_balance', [
            'rows'    => $rows,
            'keyword' => $keyword,
        ]);
    }

    public function stockMovements(): string|ResponseInterface
    {
        $dateFrom     = trim((string) $this->request->getGet('date_from'));
        $dateTo       = trim((string) $this->request->getGet('date_to'));
        $movementType = trim((string) $this->request->getGet('movement_type'));
        $rows = RepositoryServices::reportingService()->stockMovements(
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
            $movementType === '' ? null : $movementType,
        );

        $this->trackReportView('stock_movements', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'movement_type' => $movementType,
        ]);

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::reportingExportPresenter()->stockMovementsCsv($rows);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('inventory/reports/stock_movements', [
            'rows'         => $rows,
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'movement_type' => $movementType,
        ]);
    }

    public function issuances(): string|ResponseInterface
    {
        $status   = trim((string) $this->request->getGet('status'));
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));
        $rows = RepositoryServices::reportingService()->issuances(
            $status === '' ? null : $status,
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
        );

        $this->trackReportView('issuances', [
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::reportingExportPresenter()->issuancesCsv($rows);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('inventory/reports/issuances', [
            'rows'      => $rows,
            'status'    => $status,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    public function lowStock(): string|ResponseInterface
    {
        $threshold = (int) ($this->request->getGet('threshold') ?? 10);
        $rows = RepositoryServices::reportingService()->lowStock($threshold);

        $this->trackReportView('low_stock', [
            'threshold' => $threshold,
        ]);

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::reportingExportPresenter()->lowStockCsv($rows, $threshold);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('inventory/reports/low_stock', [
            'rows'       => $rows,
            'threshold'  => $threshold,
        ]);
    }

    public function fastMoving(): string|ResponseInterface
    {
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));
        $limit    = (int) ($this->request->getGet('limit') ?? 20);
        $rows = RepositoryServices::reportingService()->fastMoving(
            $dateFrom === '' ? null : $dateFrom,
            $dateTo === '' ? null : $dateTo,
            $limit,
        );

        $this->trackReportView('fast_moving', [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
        ]);

        if ($this->shouldExportCsv()) {
            $csv = RepositoryServices::reportingExportPresenter()->fastMovingCsv($rows);

            return $this->csvResponse(
                $csv['filename'],
                $csv['headers'],
                $csv['rows'],
            );
        }

        return view('inventory/reports/fast_moving', [
            'rows'      => $rows,
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
