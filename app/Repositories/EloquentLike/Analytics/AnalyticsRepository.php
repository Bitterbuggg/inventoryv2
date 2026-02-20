<?php

namespace App\Repositories\EloquentLike\Analytics;

use App\Models\Analytics\AnalyticsDailyMetricModel;
use App\Models\Analytics\AnalyticsEventModel;
use App\Repositories\Contracts\Analytics\AnalyticsRepositoryInterface;
use RuntimeException;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function createEvent(array $data): int
    {
        $id = $this->eventModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create analytics event.');
        }

        return (int) $id;
    }

    public function listEvents(array $filters = [], int $limit = 200): array
    {
        $model = $this->eventModel();

        if (! empty($filters['event_name'])) {
            $model->like('event_name', (string) $filters['event_name']);
        }

        if (! empty($filters['module'])) {
            $model->where('module', (string) $filters['module']);
        }

        if (! empty($filters['actor_id'])) {
            $model->where('actor_id', (int) $filters['actor_id']);
        }

        if (! empty($filters['date_from'])) {
            $model->where('created_at >=', (string) $filters['date_from'] . ' 00:00:00');
        }

        if (! empty($filters['date_to'])) {
            $model->where('created_at <=', (string) $filters['date_to'] . ' 23:59:59');
        }

        return $model
            ->orderBy('id', 'DESC')
            ->findAll(max(1, min(1000, $limit)));
    }

    public function countAllEvents(): int
    {
        return $this->eventModel()->countAllResults();
    }

    public function countEventsSince(string $dateTime): int
    {
        return $this->eventModel()
            ->where('created_at >=', $dateTime)
            ->countAllResults();
    }

    public function countByModuleSince(string $dateTime): array
    {
        $builder = $this->eventModel()
            ->select('module, COUNT(*) as total', false)
            ->where('created_at >=', $dateTime)
            ->groupBy('module')
            ->orderBy('total', 'DESC')
            ->builder();

        return $builder->get()->getResultArray();
    }

    public function topEventNamesSince(string $dateTime, int $limit = 10): array
    {
        $builder = $this->eventModel()
            ->select('event_name, COUNT(*) as total', false)
            ->where('created_at >=', $dateTime)
            ->groupBy('event_name')
            ->orderBy('total', 'DESC')
            ->builder();

        return $builder->limit(max(1, min(50, $limit)))->get()->getResultArray();
    }

    public function topRoutesSince(string $dateTime, int $limit = 10): array
    {
        $builder = $this->eventModel()
            ->select('route, COUNT(*) as total', false)
            ->where('created_at >=', $dateTime)
            ->where('route IS NOT NULL', null, false)
            ->groupBy('route')
            ->orderBy('total', 'DESC')
            ->builder();

        return $builder->limit(max(1, min(50, $limit)))->get()->getResultArray();
    }

    public function eventTrendsByDate(string $dateFrom, string $dateTo): array
    {
        $builder = $this->eventModel()
            ->select('DATE(created_at) as metric_date, module, COUNT(*) as total', false)
            ->where('created_at >=', $dateFrom . ' 00:00:00')
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->groupBy('DATE(created_at), module')
            ->orderBy('metric_date', 'DESC')
            ->orderBy('module', 'ASC')
            ->builder();

        return $builder->get()->getResultArray();
    }

    public function aggregateEventCountsForDate(string $date): array
    {
        $builder = $this->eventModel()
            ->select('module, event_name, COUNT(*) as total', false)
            ->where('created_at >=', $date . ' 00:00:00')
            ->where('created_at <=', $date . ' 23:59:59')
            ->groupBy('module, event_name')
            ->orderBy('module', 'ASC')
            ->orderBy('event_name', 'ASC')
            ->builder();

        return $builder->get()->getResultArray();
    }

    public function aggregateModuleCountsForDate(string $date): array
    {
        $builder = $this->eventModel()
            ->select('module, COUNT(*) as total', false)
            ->where('created_at >=', $date . ' 00:00:00')
            ->where('created_at <=', $date . ' 23:59:59')
            ->groupBy('module')
            ->orderBy('module', 'ASC')
            ->builder();

        return $builder->get()->getResultArray();
    }

    public function deleteEventsOlderThan(string $dateTime): int
    {
        $builder = $this->eventModel()->builder();
        $builder->where('created_at <', $dateTime);
        $builder->delete();

        return $this->eventModel()->db->affectedRows();
    }

    public function createDailyMetric(array $data): int
    {
        $id = $this->metricModel()->insert($data, true);

        if (! is_int($id) && ! ctype_digit((string) $id)) {
            throw new RuntimeException('Failed to create analytics daily metric.');
        }

        return (int) $id;
    }

    public function listDailyMetrics(array $filters = [], int $limit = 365): array
    {
        $model = $this->metricModel();

        if (! empty($filters['metric_key'])) {
            $model->like('metric_key', (string) $filters['metric_key']);
        }

        if (! empty($filters['module'])) {
            $model->where('module', (string) $filters['module']);
        }

        if (! empty($filters['date_from'])) {
            $model->where('metric_date >=', (string) $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $model->where('metric_date <=', (string) $filters['date_to']);
        }

        return $model
            ->orderBy('metric_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll(max(1, min(2000, $limit)));
    }

    public function deleteDailyMetricsForDate(string $date): int
    {
        $builder = $this->metricModel()->builder();
        $builder->where('metric_date', $date);
        $builder->delete();

        return $this->metricModel()->db->affectedRows();
    }

    public function deleteDailyMetricsOlderThan(string $date): int
    {
        $builder = $this->metricModel()->builder();
        $builder->where('metric_date <', $date);
        $builder->delete();

        return $this->metricModel()->db->affectedRows();
    }

    private function eventModel(): AnalyticsEventModel
    {
        return new AnalyticsEventModel();
    }

    private function metricModel(): AnalyticsDailyMetricModel
    {
        return new AnalyticsDailyMetricModel();
    }
}
