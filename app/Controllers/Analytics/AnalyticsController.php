<?php

namespace App\Controllers\Analytics;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class AnalyticsController extends BaseController
{
    public function dashboard(): string
    {
        $days = (int) ($this->request->getGet('days') ?? 7);
        $summary = RepositoryServices::analyticsService()->dashboardSummary(max(1, min(30, $days)));

        // Pass everything directly to the view for instant JS pagination
        return view('analytics/dashboard', [
            'days'         => max(1, min(30, $days)),
            'total_events' => count($summary['recent_events'] ?? [])
        ] + $summary);
    }

    public function events(): string
    {
        $filters = [
            'event_name' => trim((string) $this->request->getGet('event_name')),
            'module'     => trim((string) $this->request->getGet('module')),
            'actor_id'   => trim((string) $this->request->getGet('actor_id')),
            'date_from'  => trim((string) $this->request->getGet('date_from')),
            'date_to'    => trim((string) $this->request->getGet('date_to')),
        ];

        // Fetch all matching records up to the limit
        $limit = (int) ($this->request->getGet('limit') ?? 500);
        $allEvents = RepositoryServices::analyticsService()->listEvents($filters, max(1, min(1000, $limit)));

        return view('analytics/events', [
            'rows'         => $allEvents, // Pass all rows to the browser for instant JS pagination
            'filters'      => $filters,
            'limit'        => $limit,
            'total_events' => count($allEvents)
        ]);
    }

    public function metrics(): string
    {
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo = trim((string) $this->request->getGet('date_to'));
        $module = trim((string) $this->request->getGet('module'));

        $from = $dateFrom !== '' ? $dateFrom : date('Y-m-d', strtotime('-6 days'));
        $to = $dateTo !== '' ? $dateTo : date('Y-m-d');

        $trends = RepositoryServices::analyticsService()->eventTrendsByDate($from, $to);
        $metrics = RepositoryServices::analyticsService()->listDailyMetrics([
            'date_from' => $from,
            'date_to'   => $to,
            'module'    => $module,
        ], 500);

        return view('analytics/metrics', [
            'date_from' => $from,
            'date_to'   => $to,
            'module'    => $module,
            'trends'    => $trends,
            'metrics'   => $metrics,
        ]);
    }

    public function track(): ResponseInterface
    {
        $rules = [
            'event_name' => 'required|min_length[3]|max_length[150]',
            'module'     => 'required|min_length[2]|max_length[80]',
            'reference_type' => 'permit_empty|max_length[80]',
            'reference_id' => 'permit_empty|integer',
            'metadata_json' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $metadata = [];
        $metadataRaw = $this->request->getPost('metadata_json');

        if (is_string($metadataRaw) && trim($metadataRaw) !== '') {
            $decoded = json_decode($metadataRaw, true);
            if (is_array($decoded)) {
                $metadata = $decoded;
            }
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            (string) $this->request->getPost('event_name'),
            (string) $this->request->getPost('module'),
            $this->request->getPost('reference_type') !== null ? (string) $this->request->getPost('reference_type') : null,
            $this->request->getPost('reference_id') !== null ? (int) $this->request->getPost('reference_id') : null,
            $metadata,
        );

        return $this->response->setJSON(['status' => 'ok']);
    }
}
