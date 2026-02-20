<?php

namespace App\Models\Analytics;

use CodeIgniter\Model;

class AnalyticsDailyMetricModel extends Model
{
    protected $table = 'analytics_daily_metrics';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'metric_date',
        'metric_key',
        'metric_value',
        'module',
        'dimension_json',
        'created_at',
    ];
}
