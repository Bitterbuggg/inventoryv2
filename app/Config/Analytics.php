<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Analytics extends BaseConfig
{
    /**
     * If true, IP addresses are masked before storage.
     */
    public bool $maskIpAddress = true;

    /**
     * Supported values: hash, truncate.
     */
    public string $ipMaskStrategy = 'hash';

    /**
     * Retain raw events for this many days.
     */
    public int $rawEventRetentionDays = 180;

    /**
     * Retain daily metrics for this many days.
     */
    public int $dailyMetricRetentionDays = 730;

    /**
     * Default lookback days for batch aggregation.
     */
    public int $defaultAggregationLookbackDays = 1;
}
