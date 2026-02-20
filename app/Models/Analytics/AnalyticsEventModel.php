<?php

namespace App\Models\Analytics;

use CodeIgniter\Model;

class AnalyticsEventModel extends Model
{
    protected $table = 'analytics_events';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'event_name',
        'module',
        'actor_id',
        'reference_type',
        'reference_id',
        'route',
        'method',
        'ip_address',
        'user_agent',
        'metadata_json',
        'created_at',
    ];
}
